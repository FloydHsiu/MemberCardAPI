<?php 

include('../Model/TransactionModel.php');

$transactionmodel = new TransactionModel();

$id = $transactionmodel->create();

if( $id === FALSE ){
    die('create_fail');
}else{
    echo $id;
}

$item = $transactionmodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $transactionmodel->update($id, 1) === FALSE){
    die('update_fail');
}else{
    echo 'update_success';
}

$item = $transactionmodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $transactionmodel->delete($id) === FALSE){
    die('delete_fail');
}else{
    echo 'delete_success';
}

?>