<?php 

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

function deleteTransaction($TransCode){
    global $db;
    $sql = "DELETE FROM HCEproject.TRANSAC WHERE TRANSCODE='$TransCode'";
    $db->query($sql);
}

?>