<?php

require('../dbInfo.php');
require('../Model/CardModel.php');
require('../Model/TransactionModel.php');
require('../Model/TransLogModel.php');

$db = new mysqli($hostname, $user, $pwd);

session_start();

$TransCode = $_POST['TransCode'] ;
$ComId = $_SESSION['ComId'];

$result = $db->query("SELECT * FROM HCEproject.TRANSAC WHERE TRANSCODE='".$TransCode."'");
if($result->num_rows > 0){
    $temp = $result->fetch_assoc();
    $trans_comid = $temp['COMID'];
    $trans_cardnum = $temp['CARDNUM'];
    $trans_expire = $temp['EXPIRE'];
    if($trans_comid == $ComId){
        if($trans_expire > time()){
            //Start Transaction
            $card = getCard($trans_comid, $trans_cardnum);
            if(useCard($card, 1)){
                deleteTransaction($TransCode);
                createTransLog($card['ComId'], $card['CardNum'], $TransCode, $card['AccId'], $_SESSION['ID']);
                echo json_encode(array('state'=>'success'));
            }
            else{
                echo json_encode(array('state'=>'use card fail'));
            }
        }
        else{
            echo json_encode(array('state'=>'TransactionOverTime'));
        }
    }
    else{
        echo json_encode(array('state'=>'invalid ComId'));
    }
}
else{
    echo json_encode(array('state'=>'No Transaction'));
}

?>