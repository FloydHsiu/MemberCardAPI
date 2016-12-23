<?php 

include("../Model/CompanyModel.php");

$companymodel = new CompanyModel();

$id = $companymodel->insert("Test");

if($id === FALSE){
    die("insert_fail");
}else{
    echo $id;
}

$item = $companymodel->select($id);

if($item === FALSE){
    die("select_fail");
}else{
    echo "select: ".$item['NAME']." ".$item['ICON'];
}

if($companymodel->update($id, "TEST_UPDATE", "./image/icon.jpg") === FALSE){
    die("update_fail");
}else{
    echo "update success";
}

$item = $companymodel->select($id);

if($item === FALSE){
    die("select_fail");
}else{
    echo "select: ".$item['NAME']." ".$item['ICON'];
}

// if($companymodel->delete($id) === FALSE){
//     die("delete_fail");
// }else{
//     echo "delete success";
// }

$selectall = $companymodel->selectAll();

if($selectall === FALSE){
    die("selectAll_fail");
}else{
    echo json_encode($selectall);
}

?>