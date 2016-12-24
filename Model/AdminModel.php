<?php 

class AdminModel{
    var $db;
    var $db_name = "hceproject.ADMIN";

    function AdminModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
    }

    function insert($value_contenter){
        // check input value
        $adminid = $this->check_adminid_value( $value_contenter['ADMINID'] );
        $pwd = $this->check_pwd_value( $value_contenter['PWD'] );
        $name = $this->check_name_value( $value_contenter['NAME'] );
        $companyid = $value_contenter['COMPANYID'];
        // insert
        $insert_sql = "INSERT INTO $this->db_name(ADMINID,PWD,COMPANYID,NAME) VALUES ('$adminid', '$pwd', $companyid, '$name')";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }else{
            return FALSE;
        }
    }

    function update($id, $value_contenter){
        // check input value
        $pwd = $this->check_pwd_value( $value_contenter['PWD'] );
        $name = $this->check_pwd_value( $value_contenter['NAME'] );
        // update
        $update_sql = "UPDATE $this->db_name SET PWD='$pwd', NAME='$name' WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function delete($id){
        //delete
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
        if( $item->num_rows > 0 ){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }

    function verify($adminid, $pwd){
        // check input value
        $adminid = $this->check_adminid_value($adminid);
        $pwd = $this->check_pwd_value($pwd);
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE ADMINID='$adminid' and PWD='$pwd'";
        $item = $this->db->query($select_sql);
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }

    function check_adminid_value($adminid){
        return $adminid;
    }

    function check_pwd_value($pwd){
        return $pwd;
    }

    function check_name_value($name){
        return $name;
    }
}

?>