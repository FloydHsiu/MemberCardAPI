<?php

session_start();

$output = array('isLogin'=> false, 'isAdmin'=>$_SESSION['valid']);

if($_SESSION['ComId'] != 0){
    $output['isAdmin'] = true;
}

echo json_encode($output);

?>