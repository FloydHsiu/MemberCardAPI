<?php 
    require('../dbInfo.php');

    $db = new mysqli($hostname, $user, $pwd);

    session_start();

    if($_SESSION['valid'] === true){
        $accid = $_SESSION['ID'];
        $result = $db->query("SELECT * FROM HCEproject.CARD WHERE ACCID = ".$accid);

        $list = array();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $temp = array(
                    'ComId'=> $row['ComId'], 
                    'CardNum' => $row['CardNum'], 
                    'CardType' => $row['CardType'],
                    'ExpireTime' => $row['ExpireTime'],
                    'CardLevel' => $row['CardLevel']);
                array_push($list, $temp);
            }
        }
        
        echo json_encode(array('CardList' => $list, 'state'=> 'success'));   
    }
    else{
        echo json_encode(array('state'=> 'no login'));
    }
?>