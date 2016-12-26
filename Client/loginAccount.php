<?php

error_reporting(0);

include('../Model/AccountModel.php');
$accountmodel = new AccountModel();
$userinfomodel = new UserInfoModel();

$accid = $_POST['ACCID'];
$pwd = $_POST['PWD'];

if( $accid != '' and $pwd != ''){
	$account = $accountmodel->verify($accid, $pwd);
	if( $account === FALSE){
		echo json_encode(array('STATE'=>false, 'ERROR'=>'verify_fail'));
	}else{
		$userinfoid = $account['USERINFOID'];
		$userinfo = $userinfomodel->select($userinfoid);
		if( $userinfo === FALSE ){
			echo json_encode(array('STATE'=>false, 'ERROR'=>'select_userinfo_fail'));
		}else{
			$isauthorizeemail = $userinfo['ISAUTHORIZEEMAIL'];
			if( $isauthorizeemail == TRUE){
				$_SESSION['ACCOUNTID'] = $account['ID'];
				$_SESSION['USERINFOID'] = $account['USERINFOID'];
				echo json_encode(array('STATE'=>true));
			}
			else{
				echo json_encode(array('STATE'=>false, 'ERROR'=>'not_authorize_email'));
			}
		}
	}
}else{
	echo json_encode(array('STATE' => false, 'ERROR'=>'empty_post_value'));
}

?>

