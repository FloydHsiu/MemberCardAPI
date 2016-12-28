<?php 

error_reporting(0);

session_start();

include('../Model/CardModel.php');
$cardmodel = new CardModel();

$option = $_GET['OPTION'];

if( strcmp($option, 'CREATE') == 0){
    createCard();
}
else if( strcmp($option, 'ADD') == 0){
    addCard();
}
else if( strcmp($option, 'SELECTBYACCOUNT') == 0){
    selectCardByAccount();
}
else if( strcmp($option, 'SELECTBYCOMPANY') == 0){
    selectCardByCompany();
}
else{
    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_option')) );
}

function createCard(){
    global $cardmodel;

    $num = $_POST['NUM'];
    $type = $_POST['TYPE'];
    $level = $_POST['LEVEL'];
    $expire = $_POST['EXPIRE'];
    $phone = $_POST['PHONE'];
    $idcard = $_POST['IDCARD'];

    if( $_SESSION['ADMINID'] == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
    }

    if( $num == '' or $type == '' or $level == '' or $expire == '' or $phone == '' or $idcard == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'empty_input_fail')));
    }

    $id = $cardmodel->create($num, $_SESSION['ADMINID'], $type, $level, $expire, $phone, $idcard);

    if( $id === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'insert_fail')));
    }else{
        echo json_encode( array('STATE'=>true));
    }
}

function addCard(){
    global $cardmodel;

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

    if( strcmp($phone, $verify['PHONE']) != 0 or strcmp($idcard, $verify['IDCARD']) != 0){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'card_verify_fail')));
    }

    if( $cardmodel->update($card['ID'], 'ACCOUNTID', $_SESSION['ACCOUNTID']) === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'update_fail')));
    }else{
        echo  json_encode( array('STATE'=>true));
    }
}

function selectCardByAccount(){
    global $cardmodel;
    if( $_SESSION['ACCOUNTID'] == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
    }

    $cards = $cardmodel->selectby('ACCOUNTID', $_SESSION['ACCOUNTID']);
    if( $cards === FALSE ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
    }

    echo json_encode( array('STATE'=>true, 'CARDS'=>json_encode($cards)) );
}

function selectCardByCompany(){
    global $cardmodel;
    if( $_SESSION['ADMINID'] == '' or $_SESSION['COMPANYID'] == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
    }

    $cards = $cardmodel->selectby('COMPANYID', $_SESSION['COMPANYID']);
    if( $cards === FALSE ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
    }

    echo json_encode( array('STATE'=>true, 'CARDS'=>json_encode($cards)) );
}

?>