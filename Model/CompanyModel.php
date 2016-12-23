<?php 

class CompanyModel{
    var $db;
    var $db_name = "hceproject.COMPANY";

    function CompanyModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }

    function insert($name){
        // check input
        $name = $this->check_name_value($name);
        // insert
        $insert_sql = "INSERT INTO $this->db_name(NAME) VALUES ('$name')";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }
        else{
            return FALSE;
        }
    }

    function update($id, $name, $icon){
        // check input
        $name = $this->check_name_value($name);
        // update
        $update_sql = "UPDATE $this->db_name SET NAME='$name', ICON='$icon' WHERE ID=$id";
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

    function selectAll(){
        // select
        $select_sql = "SELECT * FROM $this->db_name WHERE 1";
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

    function check_name_value($name){
        return $name;
    }
}

?>