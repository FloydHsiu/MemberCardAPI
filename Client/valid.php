<?php

session_start();

$output = array('isLogin'=> $_SESSION['valid'], 'isAdmin'=>false);

if($_SESSION['ComId'] != 0){
    $output['isAdmin'] = true;
}

echo json_encode($output);

?>