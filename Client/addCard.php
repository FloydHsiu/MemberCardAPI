<?php

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

session_start();

if($_SESSION['valid'] === true){
    $AccId = $_SESSION['ID'];
    $ComId = $_POST['ComId'];
    $CardNum = $_POST['CardNum'];
    $Phone = $_POST['Phone'];
    $NationId = $_POST['NationId'];

    $result = $db->query("select * from HCEproject.CARD where ComId=".$ComId." and CardNum=".$CardNum);

    if($result->num_rows > 0){
        $temp = $result->fetch_assoc();
        if($temp['Phone'] == $Phone and strcmp($temp['NationId'], $NationId) == 0){
            $update_sql = "UPDATE HCEproject.CARD SET AccId=".$AccId." WHERE ComId=".$ComId." and CardNum=".$CardNum;
            if($db->query($update_sql) === TRUE){
                echo json_encode(array('state'=> 'success'));
            }
            else{
                echo json_encode(array('state'=> 'Update error'));
            }
        }
        else{
            echo json_encode(array('state'=> 'Wrong card certificate'));
        }
    }
    else{
        echo json_encode(array('state'=> 'no this card'));
    }
}
else{
    echo json_encode(array('state'=> 'not login'));
}

$db->close();

?>
