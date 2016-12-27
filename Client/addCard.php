<?php

session_start();

include('../Model/CardModel.php');
$cardmodel = new CardModel();

$num = $_POST['NUM'];
$companyid = $_POST['COMPANYID'];
$phone = $_POST['PHONE'];
$idcard = $_POST['IDCARD'];

if( $_SESSION['ACCOUNTID'] == ''){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
}

if( $num == '' or $companyid == '' or $phone == '' or $idcard == ''){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'empty_input_fail')));
}

$card = $cardmodel->selectbynumcompanyid($num, $companyid);

if( $card === FALSE){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'card_not_exist_fail')));
}

$verifyid = $card['VERIFYID'];
$verifymodel = new VerifyModel();

$verify = $verifymodel->select($verifyid);

if($verify === FALSE){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'verify_not_exist_fail')));
}

if( strcmp($phone, $verify['PHONE']) == 0 and strcmp($idcard, $verify['IDCARD'])){
    if( $cardmodel->update($card['ID'], 'ACCOUNTID', $_SESSION['ACCOUNTID']) === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'update_fail')));
    }else{
        echo  json_encode( array('STATE'=>true));
    }
}else{
     die( json_encode( array('STATE'=>false, 'ERROR'=>'card_verify_fail')));
}

?>
