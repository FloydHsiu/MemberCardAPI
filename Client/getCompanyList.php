<?php 
    require('../dbInfo.php');

    $db = new mysqli($hostname, $user, $pwd);

    session_start();

    if($_SESSION['valid'] === true){
        $result = $db->query("SELECT * FROM HCEproject.COMPANY");

        $list = array();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                // $temp = array(
                //     'ComId'=> $row['ComId'], 
                //     'ComName'=> $row['ComName']);
                //array_push($list, $row['ComId']=> $row['ComName']);
                $list[''.$row['ComId']] = $row['ComName'];
                $list['1020'] = 'TEST';
            }
        }
        
        echo json_encode(array('CompanyList' => $list, 'state'=> 'success')); 
    }
    else{
        echo json_encode(array('state'=> 'no login'));
    }
?>