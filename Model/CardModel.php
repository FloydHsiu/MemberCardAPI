<?php 

require('../dbInfo.php');

$db = new mysqli($hostname, $user, $pwd);

function getCard($ComId, $CardNum){
    global $db;
    $result = $db->query("SELECT * FROM HCEproject.CARD WHERE ((ComId, CardNum) IN ( (".$ComId.",".$CardNum."))) and AccId=".$AccId);
    if($result->num_rows > 0){
        $temp = $result->fetch_assoc();
        return $temp;
    }
    else{
        return false;
    }
}

function useCard($Card, $Times){
    $CardType = $Card['CardType'];
    $ExpireTime = $Card['ExpireTime'];
    if(strcmp($CardType, "Permanent") === 0){
        return true;
    }
    else if( strcmp($CardType, "Limited") === 0){
        $expiretime = intval($ExpireTime);
        if($expiretime > time()){
            return true;
        }
        else{
            return false;
        }
    }
    else if( strcmp($CardType, "Times") === 0){
        $temp = split("/", $ExpireTime);
        if(count($temp) > 1){
            $all = intval($temp[1]);
            $used = intval($temp[0]);
            if($used + $Times <= $all){
                $used = $used + $Times;
                $new_expiretime = $used."/".$all;
                updateExpireTime($Card['ComId'], $Card['CardNum'], $CardType, $new_expiretime);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function updateExpireTime($ComId, $CardNum, $CardType, $ExpireTime){
    global $db;
    $sql = "UPDATE HCEproject.CARD SET CardType='$CardType', ExpireTime='$ExpireTime' WHERE ((ComId, CardNum) IN (($ComId, $CardNum)))";
    $db->query($sql);
}

?>