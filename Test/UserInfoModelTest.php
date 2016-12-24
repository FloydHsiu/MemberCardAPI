<?php 

include("../Model/UserInfoModel.php");

$userinfomodel = new UserInfoModel();

$user_data = array(
                'EMAIL'=>'test@email.com',
                 'FNAME'=>'Fname', 
                 'SNAME'=>'Sname',
                 'NICKNAME'=>'Nickname');

// $user_data = array();

$id = $userinfomodel->insert($user_data);

if( $id === FALSE){
    die('insert_fail');
}else{
    echo $id;
}

$selected = $userinfomodel->select($id);

if( $selected === FALSE){
    die('select_fail');
}else{
    echo json_encode($selected);
}

$user_data['EMAIL'] = 'testupdate@email.com';
$user_data['FNAME'] = 'FnameUpdate';
$user_data['SNAME'] = 'SnameUpdate';
$user_data['NICKNAME'] = 'NicknameUpdate';

if( $userinfomodel->update($id, $user_data) === FALSE){
    die('update_fail');
}else{
    echo 'update_success';
}

if( $userinfomodel->update_isauthorizeemail($id, TRUE) === FALSE){
    die('update_isauthorizeemail_fail');
}else{
    echo 'update_isauthorizeemail_success';
}

$selected = $userinfomodel->select($id);

if( $selected === FALSE){
    die('select_fail');
}else{
    echo json_encode($selected);
}

if( $userinfomodel->delete($id) === FALSE){
    die('delete_fail');
}else{
    echo 'delete_success';
}

?>