<?php 

include("../Model/VerifyModel.php");

$verifymodel = new VerifyModel();

$id = $verifymodel->insert("0912123123", "S123212321");

if($id === FALSE){
    die("insert_fail");
}else{
    echo $id;
}

$item = $verifymodel->select($id);

if($item === FALSE){
    die("select_fail");
}else{
    echo "select: ".$item['PHONE']." ".$item['IDCARD'];
}

if($verifymodel->update($id, "0922123123", "S223212321") === FALSE){
    die("update_fail");
}else{
    echo "update success";
}

$item = $verifymodel->select($id);

if($item === FALSE){
    die("select_fail");
}else{
    echo "select: ".$item['PHONE']." ".$item['IDCARD'];
}

if($verifymodel->delete($id) === FALSE){
    die("delete_fail");
}else{
    echo "delete success";
}

?>