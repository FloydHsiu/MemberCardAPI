<?php

error_reporting(1);

session_start();

include('../Model/TransCodeModel.php');
include('../Model/TransactionModel.php');
include('../Model/CardModel.php');

$transcodemodel = new TransCodeModel();
$transactionmodel = new TransactionModel();
$cardmodel = new CardModel();

$option = $_GET['OPTION'];

if( strcmp($option, 'START') == 0){
    startTransaction();
}
else if( strcmp($option, 'STEP0') == 0){
    transaction_step_0();
}
else if( strcmp($option, 'SELECTBYADMIN') == 0){
    selectByAdmin();
}
else if( strcmp($option, 'SELECTBYACCOUNT') == 0){
    selectByAccount();
}
else{
    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_option')) );
}

/* 
    client get Transcode and bind it with card 
    if Transcode is exist then check createtime
        if createtime is expire then delete Transcode and create new
        else check step 
            if step is > 0 return false
            else return old Transcode
*/
function startTransaction(){
    global $transactionmodel;
    global $transcodemodel;
    global $cardmodel;
    if( $_SESSION['ACCOUNTID'] == '' or $_SESSION['USERINFOID'] == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail') ) );
    }

    $cardid = $_POST['CARDID'];
    if( $cardid == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'empty_input_fail') ) );
    }

    $card = $cardmodel->select( $cardid );
    if( $card === FALSE ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'select_card_fail') ) );
    }

    if( $_SESSION['ACCOUNTID'] != $card['ACCOUNTID']){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_your_card_fail') ) );
    }

    //check if card timeout
    switch( $card['TYPE'] ){
        case 0:
            break;
        case 1:
            $createtime = strtotime( $card['EXPIRE']." 23:59:59");
            $nowtime = strtotime('now');
            if( $nowtime > $createtime ){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'card_expire_fail') ) );
            }
            break;
        case 2:
            $expire = json_decode( $card['EXPIRE']);
            if( $expire['USED'] >= $expire['ALL']){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'card_expire_fail') ) );
            }
            break;
        default:
            die( json_encode( array('STATE'=>false, 'ERROR'=>'card_type_fail') ) );
    }

    // create transcode and return to user;
    if( $card['TRANSCODEID'] == NULL ){
        $transcodeid = $transcodemodel->create();
        if( $transcodeid === FALSE ){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'create_transcode_fail') ) );
        }
        if( $cardmodel->update($cardid, 'TRANSCODEID', $transcodeid) === FALSE){
            $transcodemodel->delete($transcodeid);
            die( json_encode( array('STATE'=>false, 'ERROR'=>'update_card_fail') ) );
        }
        $transcode = $transcodemodel->select($transcodeid);
        if( $transcode === FALSE ){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'select_transcode_fail') ) );
        }
        echo json_encode( array('STATE'=>true, 'TRANSCODE'=>json_encode($transcodeid)) ) ;
    }
    else{
        $transcodeid = $card['TRANSCODEID'];
        $transcode = $transcodemodel->select($transcodeid);
        if( $transcodeid === FALSE){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'select_transcode_fail') ) );
        }
        $createtime = strtotime($transcode['CREATETIME']);
        $nowtime = strtotime('now');
        if( $nowtime - $createtime > 60 ){
            $transcodemodel->delete($transcodeid);
            die( json_encode( array('STATE'=>false, 'ERROR'=>'expire_transcode_fail') ) );
        }else{
            if( $transcode['STEP'] > 0){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'wait_step_complete') ) );
            }else{
                $transcodemodel->updateCreateTime($transcodeid);
                echo json_encode( array('STATE'=>true, 'TRANSCODE'=>json_encode($transcodeid)) ) ;
            }
        }
    }
}


function transaction_step_0(){
    global $transactionmodel;
    global $transcodemodel;
    global $cardmodel;
    if( $_SESSION['ADMINID'] == '' or $_SESSION['COMPANYID'] == ''){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'not_login_fail')) );
    }

    $cardid = $_POST['CARDID'];
    $transcodeid = $_POST['TRANSCODEID'];
    $randcode = $_POST['RANDCODE'];

    if( $cardid == '' or $transcodeid == '' or $randcode == ''){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_input_fail')) );
    }

    // check if transcodeid and randcode are exist and matched.
    $transcode_input = $transcode->select( $transcodeid );
    if( $transcode_input === FALSE){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'transcode_verify_fail')) );
    }
    else{
        if( strcmp($transcode_input['RANDCODE'], $randcode) !== 0){
            die( json_encode(array('STATE'=>false, 'ERROR'=>'transcode_verify_fail')) );
        }
        $createtime = strtotime($transcode_input['CREATETIME']);
        $nowtime = strtotime('now');
        if( $nowtime - $createtime > 60){
            die( json_encode(array('STATE'=>false, 'ERROR'=>'transcode_timeout_fail')) );
        }
    }

    // check if transcodeid of card is match with _post['TRANSCODE']
    $card_input = $cardmodel->select($cardid);
    if( $card_input === FALSE ){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'card_select_fail')) );
    }
    else{
        if( $card_input['TRANSCODEID'] !== $transcodeid){
            die( json_encode(array('STATE'=>false, 'ERROR'=>'transcode_verify_fail')) );
        }
    }

    // check card type - 0, 1, 2, type 2 need to _post use time
    $usedtime = $_POST['USEDTIMES'];
    if( $card_input['TYPE'] === 2){
        if( $usedtime == ''){
            die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_input_fail')) );
        }
    }

    //check if card timeout
    switch( $card_input['TYPE'] ){
        case 0:
            break;
        case 1:
            $createtime = strtotime( $card_input['EXPIRE']." 23:59:59");
            $nowtime = strtotime('now');
            if( $nowtime > $createtime ){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'card_expire_fail') ) );
            }
            break;
        case 2:
            $expire = json_decode( $card_input['EXPIRE']);
            if( $expire['USED'] >= $expire['ALL']){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'card_expire_fail') ) );
            }
            break;
        default:
            die( json_encode( array('STATE'=>false, 'ERROR'=>'card_type_fail') ) );
    }

    // start save the transaction log
    switch( $card_input['TYPE'] ){
        case '0': // permanent
            if( $transactionmodel->create( $cardid, $card_input['ACCOUNTID'], $_SESSION['ADMINID'], 'used') === FALSE){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'transaction_create_fail') ) );
            }
            break;
        case '1': // limited time
            if ( $transactionmodel->create( $cardid, $card_input['ACCOUNTID'], $_SESSION['ADMINID'], 'used') === FALSE){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'transaction_create_fail') ) );
            }
            break;
        case '2': // used times
            $transactionid = $transactionmodel->create( $cardid, $card_input['ACCOUNTID'], $_SESSION['ADMINID'], '$usedtime');
            if( $transactionid === FALSE){
                die( json_encode( array('STATE'=>false, 'ERROR'=>'transaction_create_fail(0)') ) );
            }
            if ( $cardmodel->useCard($cardid, $usedtime) === FALSE){
                $transactionmodel->delete( $transactionid );
                die( json_encode( array('STATE'=>false, 'ERROR'=>'transaction_create_fail(1)') ) );
            }
            break;
    }

    $transcodemodel->update($transcodeid, 1);
    echo json_encode( array('STATE'=>true) );
}

function selectByAdmin(){
    global $transactionmodel;

    if( !isset($_SESSION['ADMINID']) or !isset($_SESSION['COMPANYID']) ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
    }

    $transactions = $transactionmodel->selectby('ADMINID', $_SESSION['ADMINID']);
    if( $transactions === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
    }
    echo json_encode( array('STATE'=>true, 'TRANSACTIONS'=>json_encode($transactions)) );
}

function selectByAccount(){
    global $transactionmodel;

    if( !isset($_SESSION['ACCOUNTID']) or !isset($_SESSION['USERINFOID']) ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
    }

    $transactions = $transactionmodel->selectby('ACCOUNTID', $_SESSION['ACCOUNTID']);
    if( $transactions === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
    }
    echo json_encode( array('STATE'=>true, 'TRANSACTIONS'=>json_encode($transactions)) );
}

?>