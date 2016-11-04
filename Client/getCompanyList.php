<?php 
    require('../dbInfo.php');

    $db = new mysqli($hostname, $user, $pwd);

    session_start();

    if($_SESSION['valid'] === true){
        $result = $db->query("SELECT * FROM HCEproject.COMPANY");

        $query_list = array();
        $array_list = array();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $temp = array(
                    'ComId'=> $row['ComId'], 
                    'ComName'=> $row['ComName']);
                array_push($array_list, $temp);
                $query_list[''.$row['ComId']] = $row['ComName'];
            }
        }
        
        echo json_encode(array(
            'CompanyList' => $query_list,
            'CompanyList_array' => $array_list, 
            'state'=> 'success')); 
    }
    else{
        echo json_encode(array('state'=> 'no login'));
    }
?>