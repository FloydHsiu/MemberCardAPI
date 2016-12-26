<?php 

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

function getCard($ComId, $CardNum){
    global $db;
    $result = $db->query("SELECT * FROM HCEproject.CARD WHERE ((ComId, CardNum) IN ( ($ComId, $CardNum)))");
    if($result->num_rows > 0){
        $temp = $result->fetch_assoc();
        return $temp;
    }
    else{
        return false;
    }
}

function useCard($Card, $Times){
    $CardType = $Card['CardType'];
    $ExpireTime = $Card['ExpireTime'];
    if(strcmp($CardType, "Permanent") === 0){
        return true;
    }
    else if( strcmp($CardType, "Limited") === 0){
        $expiretime = intval($ExpireTime);
        if($expiretime > time()){
            return true;
        }
        else{
            return false;
        }
    }
    else if( strcmp($CardType, "Times") === 0){
        $temp = split("/", $ExpireTime);
        if(count($temp) > 1){
            $all = intval($temp[1]);
            $used = intval($temp[0]);
            if($used + $Times <= $all){
                $used = $used + $Times;
                $new_expiretime = $used."/".$all;
                updateExpireTime($Card['ComId'], $Card['CardNum'], $CardType, $new_expiretime);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function updateExpireTime($ComId, $CardNum, $CardType, $ExpireTime){
    global $db;
    $sql = "UPDATE HCEproject.CARD SET CardType='$CardType', ExpireTime='$ExpireTime' WHERE ((ComId, CardNum) IN (($ComId, $CardNum)))";
    $db->query($sql);
}


class CardModel{
    var $db;
    var $db_name = "hceproject.CARD";

    function TransCodeModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
    }

    function create($num, $companyid, $type, $level, $expire, $accountid, $phone, $idcard){
        include('../Model/VerifyModel.php');
        $verifymodel = new VerifyModel();
        $verifyid = $verifymodel->insert($phone, $idcard);
        if($verifyid === FALSE) return FALSE;
        else return $this->insert($num, $companyid, $type, $level, $expire, $verifyid, $accountid);
    }

    function insert($num, $companyid, $type, $level, $expire, $verifyid, $accountid){
        // check input value
        $arr_type_expire = $this->check_type_expire_value($type, $expire);
        $type = $this->arr_type_expire['TYPE'];
        $expire = $arr_type_expire['EXPIRE'];
        $level = $this->check_level_value($level);
        // insert
        $insert_sql = "INSERT INTO $this->db_name(NUM,COMPANYID,TYPE,LEVEL,EXPIRE,VERIFYID,ACCOUNTID) VALUES ('$num', $companyid, $type, $level, '$expire', $verifyid, $accountid)";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }else{
            return FALSE;
        }
    }

    function update($id, $attr, $value, $type){
        // update
        $update_sql = "UPDATE $this->db_name SET $attr='$value' WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
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

    function check_type_expire_value($type, $expire){
        return array('TYPE'=>$type, 'EXPIRE'=>$expire);
    }

    function check_level_value($level){
        return $level;
    }
}

?>