<?php 

error_reporting(0);

session_start();

include('../Model/UserInfoModel.php');
$userinfomodel = new UserInfoModel();

$option = $_GET['OPTION'];

if( strcmp($option, 'SELECT') == 0){
    selectUserInfo();
}
else if(strcmp($option, 'EMAILAUTH') == 0){
    verifyEmail();
}
else{
    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_option')) );
}

function selectUserInfo(){
    global $userinfomodel;
    if( $_SESSION['ACCOUNTID'] == '' or $_SESSION['USERINFOID'] == ''){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
    }

    $userinfo = $userinfomodel->select($_SESSION['USERINFOID']);

    if( $userinfo === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
    }

    echo json_encode( array('STATE'=>true, 'USERINFO'=>json_encode($userinfo)));
}

function updateEmailAuthorize(){
    global $userinfomodel;
    if( !isset($_GET['VERIFY']) ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'empty_input_fail')));
    }

    $verify = base64_decode($_GET['VERIFY']);
    if( $verify === FALSE ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'decode_fail')));
    }

    $info = json_decode($verify);
    if( $info === FALSE ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'decode_fail')));
    }

    $userinfoid = $info['USERINFOID'];

    if( $userinfomodel->update_isauthorizeemail($userinfoid, true) === FALSE){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'update_fail')));
    }

    echo json_encode( array('STATE'=>true));
}


?>