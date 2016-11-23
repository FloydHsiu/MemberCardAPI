<?php

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

session_start();

$TransCode = $_POST['TransCode'] ;
$ComId = $_SESSION['ComId'];


$result = $db->query("SELECT * FROM HCEproject.TRANSAC WHERE TRANSCODE=\'".$TransCode."\'");
if($result->num_rows > 0){
    $temp = $result->fetch_assoc();
    $trans_comid = $temp['COMID'];
    $trans_cardnum = $temp['CARDNUM'];
    $trans_expire = $temp['EXPIRE'];
    if($trans_comid == $ComId && $trans_expire < time()){
        //Start transaction
        echo json_encode(array('state'=>'success'));
    }
    else{
        echo json_encode(array('state'=>'invalid'));
    }
}
else{
    echo json_encode(array('state'=>'No Transaction'));
}

?>