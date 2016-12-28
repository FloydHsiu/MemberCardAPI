<?php
error_reporting(0);

session_start();

include('../Model/UserInfoModel.php');

$userinfomodel = new UserInfoModel();

if( $_SESSION['ACCOUNTID'] == '' or $_SESSION['USERINFOID'] == ''){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
}

$userinfo = $userinfomodel->select($_SESSION['USERINFOID']);

if( $userinfo === FALSE){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
}

echo json_encode( array('STATE'=>true, 'USERINFO'=>json_encode($userinfo)));

?>