<?php 

error_reporting(0);

session_start();

include('../Model/CardModel.php');
$cardmodel = new CardModel();

$num = $_POST['NUM'];
$type = $_POST['TYPE'];
$level = $_POST['LEVEL'];
$expire = $_POST['EXPIRE'];
$phone = $_POST['PHONE'];
$idcard = $_POST['IDCARD'];

if( $_SESSION['ADMINID'] == ''){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
}

if( $num != '' and $type != '' and $level != '' and $expire != '' and $phone != '' and $idcard != ''){
    $id = $cardmodel->create($num, $_SESSION['ADMINID'], $type, $level, $expire, $phone, $idcard);
    if( $id === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'insert_fail')));
    }else{
        echo json_encode( array('STATE'=>true));
    }
}else{
    die( json_encode( array('STATE'=>false, 'ERROR'=>'empty_input_fail')));
}

?>