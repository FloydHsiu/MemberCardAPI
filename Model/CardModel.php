<?php

class CardModel{
    var $db;
    var $db_name = "hceproject.CARD";
    var $verifymodel;

    function CardModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        include('VerifyModel.php');
        $this->verifymodel = new VerifyModel();
    }

    function create($num, $companyid, $type, $level, $expire, $phone, $idcard){
        $verifyid = $this->verifymodel->insert($phone, $idcard);
        if( $this->selectbynumcompanyid($num, $companyid) !== FALSE){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'card_exist_fail')));
        }
        if($verifyid === FALSE){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'insert_verify_fail')));
        }
        else{
            $cardid = $this->insert($num, $companyid, $type, $level, $expire, $verifyid);
            if( $cardid === FALSE){
                $verifymodel->delete($verifyid);
            }else{
                return $cardid;
            }
        }
    }

    function insert($num, $companyid, $type, $level, $expire, $verifyid){
        // check input value
        $arr_type_expire = $this->check_type_expire_value($type, $expire);
        $type = $arr_type_expire['TYPE'];
        $expire = $arr_type_expire['EXPIRE'];
        $level = $this->check_level_value($level);
        // insert
        $insert_sql = "INSERT INTO $this->db_name(NUM,COMPANYID,TYPE,LEVEL,EXPIRE,VERIFYID) VALUES ('$num', $companyid, $type, $level, '$expire', $verifyid)";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }else{
            return FALSE;
        }
    }

    function update($id, $attr, $value){
        // update
        $update_sql = "UPDATE $this->db_name SET $attr='$value' WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function useCard($id, $times){
        $card = $this->select($id);
        if( $card === FALSE ){
            return FALSE;
        }

        $expire = $card['EXPIRE'];
        if( $card['TYPE'] == 2){
            $temp = json_decode( $expire );
            if( $temp['USED'] + $times > $temp['ALL']){
                return FALSE;
            }
            else{
                $temp['USED'] = $times + $temp['USED'];
            }
        }
        return $this->update($id, "EXPIRE", json_encode($temp));
    }

    function delete($id){
        // delete 
        $delete_sql = "DELETE FROM $this->db_name WHERE ID=$id";
        if( $this->db->query($delete_sql) === TRUE ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function select($id){
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE ID=$id";
        $item = $this->db->query($select_sql);
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }

    function selectby($attr, $value){
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE $attr=$value";
        $item = $this->db->query($select_sql);
        if($item->num_rows > 0){
            $selected = array();
            while( $temp = $item->fetch_assoc()){
                array_push($selected, $temp);
            }
            return $selected;
        }else{
            return FALSE;
        }
    }

    function selectbynumcompanyid($num, $companyid){
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE NUM='$num' AND COMPANYID=$companyid";
        $item = $this->db->query($select_sql);
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }

    function check_type_expire_value($type, $expire){
        if( $type == 0){
            return array('TYPE'=>$type, 'EXPIRE'=>$expire);
        }
        else if( $type == 1){
            if( strtotime($expire) === FALSE){
                die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_expire_value')) );
            }
            return array('TYPE'=>$type, 'EXPIRE'=>$expire);
        }
        else if( $type == 2){
            $decode = json_decode( $expire );
            if( $decode === NULL){
                die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_expire_value')) );
            }
            else{
                if( $decode['ALL'] == '' and $decode['USED'] == ''){
                    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_expire_value')) );
                }
                return array('TYPE'=>$type, 'EXPIRE'=>$expire);
            }
        }
        else{
            die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_type_value')) );
        }
    }

    function check_level_value($level){
        return $level;
    }

    /* 
    
    $type = 0, 1, 2
    $expire = 'p' , 'timestamp(Y-m-d)', '{'ALL':30, 'USED': 0}' 
    
    */
}

?>