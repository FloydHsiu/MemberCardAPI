<?php 

class TransactionModel{
    var $db;
    var $db_name = "hceproject.TRANSACTION";

    function TransactionModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
    }

    function create($cardid, $accountid, $adminid, $content){
        $now = date('Y-m-d H:i:s');
        $id = $this->insert($cardid, $accountid, $adminid, $content, $now);
        if( $id === FALSE){
            return FALSE;
        }else{
            return $id;
        }
    }

    function insert($cardid, $accountid, $adminid, $content, $createtime){
        // insert
        $insert_sql = "INSERT INTO $this->db_name(CARDID,ACCOUNTID,ADMINID,CONTENT,CREATETIME) VALUES ($cardid,$accountid,$adminid,'$content','$createtime')";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }else{
            return FALSE;
        }
    }

    function delete($id){
        // delete
        $delete_sql = "DELETE FROM $this->db_name WHERE ID=$id";
        if( $this->db->query($delete_sql) === TRUE){
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
}

?>