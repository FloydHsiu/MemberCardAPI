<?php 
error_reporting(0);
    
session_start();

include('../Model/CardModel.php');

$cardmodel = new CardModel();

if( $_SESSION['ACCOUNTID'] == ''){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')));
}

$cards = $cardmodel->selectby('ACCOUNTID', $_SESSION['ACCOUNTID']);
if( $cards === FALSE ){
    die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')));
}

echo json_encode( array('STATE'=>true, 'CARDS'=>json_encode($cards)) );

?>