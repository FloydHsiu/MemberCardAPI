<?php 

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

function createTransLog($comid, $cardnum, $transcode, $client, $admin){
    global $db;
    if( isset($comid) and isset($cardnum) and isset($transcode) and isset($client) and isset($admin) ){
        $now = date('Y-m-d H:i:s'); 
        $insert_sql = "INSERT INTO HCEproject.TransLog(CreateTime,ComID,CardNum,TRANSCODE,CLIENT,ADMIN) VALUES ('$now', $comid, $cardnum, '$transcode', $client, $admin)";
        if( $db->query( $insert_sql ) === TRUE){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    return FALSE;
}

function selectForConfirm($comid, $cardnum, $transcode){
    global $db;
    if( isset($comid) and isset($cardNum) and isset($transcode) ){
        $select_sql = "SELECT * FROM HCEproject.TransLog WHERE ComID=$comid and CardNum=$cardnum and TRANSCODE='$transcode'";
        $result = $db->query( $select_sql );
        if( $result->num_rows > 0){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    return FALSE;
}

function selectByAdmin($accid){
    global $db;
    if( isset($accid) ){
        $select_sql = "SELECT * FROM HCEproject.TransLog WHERE ADMIN=$accid";
        $result = $db->query($select_sql);
        if( $result->num_rows > 0){
            return $result;
        }
    }
    return FALSE;
}

function selectByClient($accid){
global $db;
    if( isset($accid) ){
        $select_sql = "SELECT * FROM HCEproject.TransLog WHERE CLIENTN=$accid";
        $result = $db->query($select_sql);
        if( $result->num_rows > 0){
            return $result;
        }
    }
    return FALSE;
}


?>