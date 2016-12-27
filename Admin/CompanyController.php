<?php 

error_reporting(0);

session_start();

include('../Model/CompanyModel.php');
$companymodel = new CompanyModel();

$option = $_GET['OPTION'];

/*
    OPTION: INSERT, UPDATE, DELETE ONLY FOR SYSTEM ADMIN
    OPTION: SELECTALL FOR USER
 */

if( strcmp($option, 'INSERT') == 0){
    adminpwdVerify();

    $name = $_POST['NAME'];
    if( $name != '' ){
        addCompany( $name );
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_name_value')) );
    }
}
else if( strcmp($option, 'UPDATE') == 0){
    adminpwdVerify();

    $name = $_POST['NAME'];
    $id = $_POST['ID'];
    $icon = $_POST['ICON'];
    if( $name != ''){
        updateCompany($id, $name, $icon);
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_name_value')) );
    }
}
else if( strcmp($option, 'DELETE') == 0){
    adminpwdVerify();

    $id = $_POST['ID'];
    deleteCompany($id);
}
else if( strcmp($option, 'SELECTALL') == 0){
    //check session isLogin?
    if( $_SESSION['ACCOUNTID'] != ''){
        selectAllCompany();
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'not_login')));
    }
}else{
    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_option')) );
}


function addCompany($name){
    global $companymodel;
    $id = $companymodel->insert($name);
    if($id === FALSE){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'insert_fail')) );
    }else{
        echo json_encode( array('STATE'=>true) );
    }
}

function updateCompany($id, $name, $icon){
    global $companymodel;   
    if( $companymodel->update($id, $name, $icon) === TRUE){
        echo json_encode( array('STATE'=>true) );
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'update_fail')) );
    }
}

function deleteCompany($id){
    global $companymodel; 
    if( $companymodel->delete($id) === TRUE){
        echo json_encode( array('STATE'=>true) );
    }else{
        dir( json_encode(array('STATE'=>false, 'ERROR'=>'delete_fail')) );
    }
}

function selectAllCompany(){
    global $companymodel;
    $items = $companymodel->selectAll();
    if( $icon === FALSE ){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'selectall_fail')) );
    }else{
        echo json_encode( array('STATE'=>true, 'COMPANY'=>json_encode($items)));
    }
}

function adminpwdVerify(){
    include('admin_pwd.php');
    $admin_pwd_post = $_POST['ADMINPWD'];
    if(strcmp($admin_pwd_post, $admin_pwd) != 0){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'verify_error')) );
    }
}

?>