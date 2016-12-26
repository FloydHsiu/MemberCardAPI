<?php 

class AccountModel{
    var $db;
    var $db_name = "hceproject.ACCOUNT";
    var $userinfomodel;

    function AccountModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        include('UserInfoModel.php');
        $this->userinfomodel = new UserInfoModel();
    }

    function insert($accid, $pwd, $value_contenter){
        // check input: accid, pwd
        $accid = $this->check_accid_value($accid);
        $pwd = $this->check_pwd_value($pwd);
        // check if accid is exist
        if( $this->selectbyaccid($accid) === FALSE){
            //the accid is not exist yet
        }
        else{
            die(json_encode(array('STATE' => false, 'ERROR'=>'accid_is_exist')));
        }
        // insert userinfo
        $userinfoid = $this->userinfomodel->insert($value_contenter);
        if( $userinfoid === FALSE){
            die(json_encode(array('STATE' => false, 'ERROR'=>'insert_userinfo_fail_is')));
        }
        //insert
        $insert_sql = "INSERT INTO $this->db_name(ACCID,PWD,USERINFOID) VALUES ('$accid', '$pwd', $userinfoid)";
        if( $this->db->query( $insert_sql ) === TRUE){
            return $this->db->insert_id;
        }else{
            // if create account fail then delete userinfo
            $userinfomodel->delete($userinfoid);
            return FALSE;
        }
    }

    function update($id, $pwd){
        // check input
        $pwd = $this->check_pwd_value($pwd);
        // update
        $update_sql = "UPDATE $this->db_name SET PWD='$pwd' WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function delete($id){
        // select userinfoid
        $item = $this->select($id);
        if( $item === FALSE){
            return FALSE;
        }
        $userinfoid = $item['USERINFOID'];
        // delete
        $delete_sql = "DELETE FROM $this->db_name WHERE ID=$id";
        if( $this->db->query($delete_sql) === TRUE){
            //also delete userinfo
            return $this->userinfomodel->delete($userinfoid);
        }
        else{
            return FALSE;
        }
    }

    function select($id){
        //select
        $select_sql = "SELECT * FROM $this->db_name WHERE ID=$id";
        $item = $this->db->query($select_sql);
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }

    function selectbyaccid($accid){
        //select
        $select_sql = "SELECT * FROM $this->db_name WHERE ACCID='$accid'";
        $item = $this->db->query($select_sql);
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }

    function verify($accid, $pwd){
        //check input: accid, pwd
        $accid = $this->check_accid_value($accid);
        $pwd = $this->check_pwd_value($pwd);
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE ACCID='$accid' AND PWD='$pwd'";
        $item = $this->db->query($select_sql);
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }
    
    function check_accid_value($accid){
        return $accid;
    }

    function check_pwd_value($pwd){
        return $pwd;
    }

}

?>