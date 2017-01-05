<?php 

require('../Model/TransLogModel.php');

$ComId = $_POST['ComId'];
$CardNum = $_POST['CardNum'];
$TransCode = $_POST['TransCode'];

echo json_encode( array('STATE'=> selectForConfirm($ComId, $CardNum, $TransCode)));

?>