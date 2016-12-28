<?php 

error_reporting(0);

session_start();

include('../Model/UserInfoModel.php');
$userinfomodel = new UserInfoModel();

$option = $_GET['OPTION'];

if( strcmp($option, 'SELECT') == 0){
    selectUserInfo();
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


?>