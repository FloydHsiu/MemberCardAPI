<?php 

include("../Model/AccountModel.php");

$accountmodel = new AccountModel();

$user_data = array(
                'EMAIL'=>'test@email.com',
                 'FNAME'=>'Fname', 
                 'SNAME'=>'Sname',
                 'NICKNAME'=>'Nickname');

$id = $accountmodel->insert('accid', 'pwd', $user_data);
if($id === FALSE){
    die('insert_fail');
}else{
    echo $id;
}

$item = $accountmodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $accountmodel->update($id, 'pwdupdate') === FALSE){
    die('update_fail');
}else{
    echo 'update_success';
}

$item = $accountmodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $accountmodel->verify('accid', 'pwdupdate') === FALSE){
    echo 'verify_fail';
}else{
    echo 'verify_success';
}

if( $accountmodel->verify('accid', 'pwd') === FALSE){
    echo 'verify_fail';
}else{
    echo 'verify_success';
}

if( $accountmodel->delete($id) === FALSE ){
    die('delete_fail');
}else{
    echo 'delete_success';
}

?>