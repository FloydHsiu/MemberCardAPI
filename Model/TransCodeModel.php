<?php 

class TransCodeModel{
    var $db;
    var $db_name = "hceproject.TRANSCODE";

    function TransCodeModel(){
        require('../dbInfo.php');
        $this->db = new mysqli($hostname, $user, $pwd);
        if ($this->db->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
    }

    function create(){
        include('../random.php');
        $try_times = 5;
        for($i=0; $i<=$try_times; $i++){
            // get random string
            $code = get_randString(10);
            $createtime = date('Y-m-d H:i:s'); 
            $step = 0;
            $id = $this->insert($code, $createtime, $step);
            if( $id === FALSE){

            }else{
                return $id;
            }
        }
        return FALSE;
    }

    function insert($code, $createtime, $step){
        // insert
        $insert_sql = "INSERT INTO $this->db_name(RANDCODE,CREATETIME,STEP) VALUES ('$code','$createtime', $step)";
        if( $this->db->query($insert_sql) === TRUE){
            return $this->db->insert_id;
        }else{
            return FALSE;
        }
    }

    function update($id, $step){
        // update
        $update_sql = "UPDATE $this->db_name SET STEP=$step WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function updateCreateTime($id){
        $createtime = date('Y-m-d H:i:s');
        // update
        $update_sql = "UPDATE $this->db_name SET CREATETIME='$createtime' WHERE ID=$id";
        if( $this->db->query($update_sql) === TRUE ){
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
        if( $item->num_rows > 0){
            return $item->fetch_assoc();
        }else{
            return FALSE;
        }
    }
}

?>