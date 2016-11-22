<?php

require('../dbInfo.php');
require('../random.php');

session_start();

function isCardAndAccMatch($ComId, $CardNum, $AccId){
    $db = new mysqli($hostname, $user, $pwd);
    $result = $db->query("SELECT * FROM HECproject.CARD WHERE ((ComId, CardNum) IN ( (".$ComId.",".$CardNum."))) and AccId=".$AccId);
    if($result->num_rows > 0){
        return true;
    }
    else{
        return false;
    }
    $db->close();
}

$db = new mysqli($hostname, $user, $pwd);

$ComId = $_POST['ComId'];
$CardNum = $_POST['CardNum'];
$AccId = $_SESSION['ID'];

if($_SESSION['valid']){
    $temp = isCardAndAccMatch($ComId, $CardNum, $AccId);
    if($temp){
        $transcode = get_randString(20);
        $expire = time() + 60*10;
        $db->query("INSERT INTO HCEproject.TRANSAC(COMID, CARDNUM, TRANSCODE, EXPIRE)".
                    "VALUE (".$ComId.",".$CardNum.",'".$transcode."',".$expire.")");
        echo json_encode(array('state'=>'success', 'transcode'=>$transcode));
    }
    else{
        echo json_encode(array('state'=>'no card access', 'AccId'=>$AccId, 'ComId'=>$ComId, 'Cardnum'=>$CardNum));
    }
}
else{
    echo json_encode(array('state'=>'not login'));
}



?>
