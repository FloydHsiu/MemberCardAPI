<?php 

include('../Model/AdminModel.php');

$adminmodel = new AdminModel();

$admin_data = array(
    'ADMINID'=>'adminid',
    'PWD'=>'pwd',
    'COMPANYID'=> 1,
    'NAME'=> 'name'
);

$id = $adminmodel->insert($admin_data);
if( $id === FALSE){
    die('insert_fail');
}else{
    echo $id;
}

$item = $adminmodel->select($id);
if( $item === FALSE){
    die('select_fail\n');
}else{
    echo json_encode($item);
}

$admin_data['PWD'] = 'pwdupdate';
$admin_data['NAME'] = 'nameupdate';

if( $adminmodel->update($id, $admin_data) === FALSE ){
    die('update_fail');
}else{
    echo 'update_success';
}

$item = $adminmodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $adminmodel->verify('adminid', 'pwd') === FALSE ){
    echo 'verify_fail';
}else{
    echo 'verify_success';
}

if( $adminmodel->verify('adminid', 'pwdupdate') === FALSE ){
    echo 'verify_fail';
}else{
    echo 'verify_success';
}

if( $adminmodel->delete($id) === FALSE){
    die('delete_fail');
}else{
    echo 'delete_success';
}

?>