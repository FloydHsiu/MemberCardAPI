<?php

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

$acc = $_POST['ACC'];
$pwd = $_POST['PWD'];

session_start();

$result = $db->query("SELECT * FROM HCEproject.MEMBER WHERE ACCID = '".$acc."' and PWD ='".$pwd."'");
if ($result->num_rows > 0){
	echo json_encode(array('valid' => true));
	$temp = $result->fetch_assoc();
	$_SESSION['valid']=true;
	$_SESSION['id']= $acc;
	$_SESSION['ID'] = $temp['ID'];
	$_SESSION['ComId'] = $temp['ComId'];
}
else{
	echo json_encode(array('valid' => false));
	$_SESSION['valid']=false;
}

?>

