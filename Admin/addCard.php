<?php 

require('../dbInfo.php');
require('../random.php');

$db = new mysqli($hostname, $user, $pwd);

session_start();

function is_card_exist($ComId, $CardId){
    global $db;
    $query = $db->query('SELECT * FROM HCEproject.CARD WHERE ComId='.$ComId.' and CardId='.$CardId);
    if($query->num_rows > 0){
        return true;
    }
    else{
        return false;
    }
}

function get_valid_cardnum($ComId){
    $i = 0;
    while($i < 10){
         $CardNum = get_rand(1, 9999999999);
         if(is_card_exist($ComId)){
             $i = $i + 1;
         }
         else{
             return $CardNum;
         }
    }
    return false;
}

function add_card($ComId, $CardNum, $CardType, $ExpireTime, $NationId, $CardLevel, $Phone){
    global $db;
    $query = $db->query('INSERT INTO HCEproject.CARD (ComId, CardNum, CardType, ExpireTime, NationId
                        ,CardLevel, Phone)'.' VALUES ('
                        .$ComId.','.$CardNum.',\''.$CardType.'\',\''.$ExpireTime
                        .'\',\''.$NationId.'\',\''.$CardLevel.'\','.$Phone.')');
    return $query;
}

$ComId = $_SESSION['ComId'];

echo $CardNum = get_rand(1, 9999999999);

if($_SESSION['valid'] === true){
    if($ComId != 0){
        /* ComId, CardNum, CardType, ExpireTime, NationId, CardLevel, Phone, AccId*/
        $CardNum = get_valid_cardnum($ComId);
        if($CardNum != false){
            $CardType = $_POST['CardType'];
            $ExpireTime = $_POST['ExpireTime'];
            if( $CardType != '' && $ExpireTime != ''){//Check CardType, ExpireTime data format
            // Permanent, Limited, Times
                $CardLevel = $_POST['CardLevel'];
                if($CardLevel != ''){//Check CardLevel data format
                    $NationId = $_POST['NationId'];
                    $Phone = $_POST['Phone'];
                    if($NationId != '' && $Phone!= ''){// Check NationId, Phone Type
                        add_card($ComId, $CardNum, $CardType, $ExpireTime, $NationId, $CardLevel, $Phone);
                    }
                    else{
                        echo json_encode(array('state'=> 'wrong NationId, Phone type'));
                    }
                }
                else{
                    echo json_encode(array('state'=> 'wrong CardLevel type'));
                }
            }
            else{
                echo json_encode(array('state'=> 'wrong CardType, ExpireTime type'));
            }
        }
        else{
            echo json_encode(array('state'=> 'get cardnum error'));
        }
    }
    else{
        echo json_encode(array('state'=> 'not admin'));
    }
}
else{
    echo json_encode(array('state'=> 'not login'));
}


?>