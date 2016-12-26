<?php 

include('../Model/TransCodeModel.php');

$transcodemodel = new TransCodeModel();

$id = $transcodemodel->create();

if( $id === FALSE ){
    die('create_fail');
}else{
    echo $id;
}

$item = $transcodemodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $transcodemodel->update($id, 1) === FALSE){
    die('update_fail');
}else{
    echo 'update_success';
}

$item = $transcodemodel->select($id);
if( $item === FALSE){
    die('select_fail');
}else{
    echo json_encode($item);
}

if( $transcodemodel->delete($id) === FALSE){
    die('delete_fail');
}else{
    echo 'delete_success';
}

?>