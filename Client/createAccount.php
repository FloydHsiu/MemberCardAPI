<?php
error_reporting(0);

include('../Model/AccountModel.php');
$accountmodel = new AccountModel();

$accid = $_POST['ACCID'];
$pwd = $_POST['PWD'];
$email = $_POST['EMAIL'];

if( $accid != '' and $pwd != '' and $email != ''){
	$value_contenter = array('EMAIL'=>$email, 'FNAME'=>'', 'SNAME'=>'', 'NICKNAME'=>'');
	$id = $accountmodel->insert($accid, $pwd, $value_contenter);
	if( $id === FALSE){
		echo json_encode(array('STATE' => false, 'ERROR'=>'insert_account_fail'));
	}else{
		echo json_encode(array('STATE' => true));
	}
}else{
	echo json_encode(array('STATE' => false, 'ERROR'=>'empty_post_value'));
}

?>