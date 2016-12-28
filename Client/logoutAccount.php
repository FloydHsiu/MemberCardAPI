<?php 

session_start();

$_SESSION['ACCOUNTID'] = '';
$_SESSION['USERINFO'] = '';

echo json_encode( array('STATE'=>true) );
?>