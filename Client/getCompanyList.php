<?php 
    require('../dbInfo.php');

    $db = new mysqli($hostname, $user, $pwd);

    session_start();

    if($_SESSION['valid'] === true){
        $result = $db->query("SELECT * FROM HCEproject.COMPANY");

        $list = array();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $temp = array(
                    'ComId'=> $row['Comid'], 
                    'ComName'=> $row['ComName']);
                array_push($list, $temp);
            }
        }
        
        echo json_encode(array('CardList' => $list, 'state'=> 'success')); 
    }
    else{
        echo json_encode(array('state'=> 'no login'));
    }
?>