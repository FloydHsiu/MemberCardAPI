<?php 

require('dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

$acc = $_POST['ACC'];
$pwd = $_POST['PWD'];

if($acc !== '' && $pwd !== ''){
	$IsAccExist = $db->query("SELECT * FROM HCEproject.MEMBER WHERE ACCID = '".$acc."'");
	if ($result->num_rows > 0){
		echo json_encode(array('valid' => false));
	}
	else{
		echo json_encode(array('valid' => true));
		//Insert Account
		$db->query("INSERT INTO HCEproject.MEMBER(ACCID, PWD) VALUE ('".$acc."','".$pwd."')");
	}
}
else{
	echo json_encode(array('valid' => false));
}


?>