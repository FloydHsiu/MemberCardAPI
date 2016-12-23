<?php 

class VerifyModel{
    var $db;
    var $db_name = "hceproject.VERIFY";

    function VerifyModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
    }

    function insert($phone, $idcard){
        // check input
        $phone = $this->check_phone_value($phone);
        $idcard = $this->check_idcard_value($idcard);
        // insert    
        $insert_sql = "INSERT INTO $this->db_name(PHONE, IDCARD) VALUES ('$phone', '$idcard')";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }
        else{
            return FALSE;
        }
    }

    function update($id, $phone, $idcard){
        // check input
        $phone = $this->check_phone_value($phone);
        $idcard = $this->check_idcard_value($idcard);
        // update
        $update_sql = "UPDATE $this->db_name SET PHONE='$phone', IDCARD='$idcard' WHERE ID=$id";
        if($this->db->query($update_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function delete($id){
        // delete
        $delete_sql = "DELETE FROM $this->db_name wHERE ID=$id";
        if($this->db->query($delete_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function select($id){
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE ID=$id";
        $item = $this->db->query($select_sql);
        if($item->num_rows > 0){
            $temp = $item->fetch_assoc();
            return $temp;
        }else{
            return FALSE;
        }
    }

    function check_phone_value($phone){
        return $phone;
    }

    function check_idcard_value($idcard){
        return $idcard;
    }
}

?>