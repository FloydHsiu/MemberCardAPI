<?php 

class UserInfoModel{
    var $db;
    var $db_name = "hceproject.USERINFO";

    function UserInfoModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
    }

    function insert($value_contenter){
        // check input
        $email = $this->check_email_value($value_contenter['EMAIL']);
        $fname = $this->check_fname_value($value_contenter['FNAME']);
        $sname = $this->check_sname_value($value_contenter['SNAME']);
        $nickname = $this->check_nickname_value($value_contenter['NICKNAME']);
        // insert
        $insert_sql = "INSERT INTO $this->db_name(EMAIL, FNAME, SNAME, NICKNAME) VALUES ('$email', '$fname', '$sname', '$nickname')";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }else{
            return FALSE;
        }
    }

    function update($id, $value_contenter){
        // check input
        $email = $this->check_email_value($value_contenter['EMAIL']);
        $fname = $this->check_fname_value($value_contenter['FNAME']);
        $sname = $this->check_sname_value($value_contenter['SNAME']);
        $nickname = $this->check_nickname_value($value_contenter['NICKNAME']);
        // update
        $update_sql = "UPDATE $this->db_name SET EMAIL='$email', FNAME='$fname', SNAME='$sname', NICKNAME='$nickname' WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function update_isauthorizeemail($id, $isauthorize){
        $update_sql = "UPDATE $this->db_name SET ISAUTHORIZEEMAIL=$isauthorize WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE){
            return TRUE;
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
        if($item->num_rows > 0){
            $temp = $item->fetch_assoc();
            return $temp;
        }else{
            return FALSE;
        }
    }

    function check_email_value($email){
        return $email;
    }

    function check_fname_value($fname){
        return $fname;
    }

    function check_sname_value($sname){
        return $sname;
    }

    function check_nickname_value($nickname){
        return $nickname;
    }
}

?>
