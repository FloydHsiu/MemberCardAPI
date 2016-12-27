<?php 
error_reporting(0);

session_start();

include('../Model/AdminModel.php');
$adminmodel = new AdminModel();

$option = $_GET['OPTION'];

if( strcmp($option, 'CREATEBYSYS') == 0){
    $adminid = $_POST['ADMINID'];
    $pwd = $_POST['PWD'];
    $name = $_POST['NAME'];
    $companyid = $_POST['COMPANYID'];
    if( $adminid != '' and $pwd != '' and $companyid != ''){
        createAdminBySysAdmin($adminid, $pwd, $name, $companyid);
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_input_fail')) );
    }
}
else if( strcmp($option, 'CREATEBYADMIN') == 0){
    $adminid = $_POST['ADMINID'];
    $pwd = $_POST['PWD'];
    $name = $_POST['NAME'];
    if( $adminid != '' and $pwd != ''){
        createAdminByAdmin($adminid, $pwd, $name);
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_input_fail')) );
    }
}
else if( strcmp($option, 'UPDATE') == 0){
    $pwd = $_POST['PWD'];
    $name = $_POST['NAME'];
    if( $pwd != ''){
        updateAdmin($pwd, $name);
    }else{
        die( json_encode(array('STATE'=>false, 'ERROR'=>'empty_input_fail')) );
    }
}
else if( strcmp($option, 'SELECT') == 0){
    selectAdmin();
}
else if( strcmp($option, 'LOGIN') == 0){
    $adminid = $_POST['ADMINID'];
    $pwd = $_POST['PWD'];
    loginAdmin($adminid, $pwd);
}
else{
    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_option')) );
}
/*
    OPTION: CREATEBYSYS FOR SYSTEM ADMIN
    OPTION: CREATEBYADMIN FOR OTHER ADMIN
    OPTION: UPDATE FOR ADMIN SELF
    OPTION: SELECT FOR ADMIN SELF
    OPTION: LOGIN FOR CREATE ADMIN SELF
 */

function createAdminBySysAdmin($adminid, $pwd, $name, $companyid){
    sysadminpwdVerify();
    global $adminmodel;
    $value_contenter = array(
        'ADMINID'=> $adminid,
        'PWD'=> $pwd,
        'NAME'=> $name,
        'COMPANYID'=> $companyid);
    $id = $adminmodel->insert($value_contenter);
    if( $id === FALSE ){
        die( json_encode( array('STATE'=>false, 'ERROR'=>'insert_fail')) );
    }else{
        echo json_encode( array('STATE'=>true));
    }
}

function createAdminByAdmin($adminid, $pwd, $name){
    if( $_SESSION['ADMINID'] != '' and $_SESSION['COMPANYID'] != ''){
        global $adminmodel;
        $value_contenter = array(
            'ADMINID'=> $adminid,
            'PWD'=> $pwd,
            'NAME'=> $name,
            'COMPANYID'=> $_SESSION['COMPANYID'] );
        $id = $adminmodel->insert($value_contenter);
        if( $id === FALSE){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'insert_fail')) );
        }else{
            echo json_encode( array('STATE'=>true));
        }
    }else{
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')) );
    }
}

function updateAdmin($pwd, $name){
    if( $_SESSION['ADMINID'] != '' and $_SESSION['COMPANYID'] != ''){
        global $adminmodel;
        $value_contenter = array('PWD'=>$pwd, 'NAME'=>$name);
        if ($adminmodel->update($_SESSION['ADMINID'], $value_contenter) === TRUE){
            echo json_encode( array('STATE'=>true));
        }else{
            die( json_encode( array('STATE'=>false, 'ERROR'=>'update_fail')) );
        }
    }else{
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')) );
    }
}

function selectAdmin(){
    if( $_SESSION['ADMINID'] != '' and $_SESSION['COMPANYID'] != ''){
        global $adminmodel;
        $item = $adminmodel->select($_SESSION['ADMINID']);
        if ( $item === FALSE){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'select_fail')) );
        }else{
            echo json_encode( array('STATE'=>true, 'ADMIN'=>json_encode(array('ID'=>$item['ID'], 'NAME'=>$item['NAME']))));
        }
    }else{
        die( json_encode( array('STATE'=>false, 'ERROR'=>'not_login_fail')) );
    }
}

function loginAdmin($adminid, $pwd){
    if( $adminid != '' and $pwd != ''){
        global $adminmodel;
        $item = $adminmodel->verify($adminid, $pwd);
        if( $item === FALSE){
            die( json_encode( array('STATE'=>false, 'ERROR'=>'verify_fail')) );
        }else{
            $_SESSION['ADMINID'] = $item['ID'];
            $_SESSION['COMPANYID'] = $item['COMPANYID'];
            echo json_encode( array('STATE'=>true)); 
        }
    }else{
        die( json_encode( array('STATE'=>false, 'ERROR'=>'empty_id_pwd')) );
    }
}

function sysadminpwdVerify(){
    include('admin_pwd.php');
    $admin_pwd_post = $_POST['ADMINPWD'];
    if(strcmp($admin_pwd_post, $admin_pwd) != 0){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'verify_error')) );
    }
}

?>