<?php 

error_reporting(0);

session_start();

include('../Model/AccountModel.php');
$accountmodel = new AccountModel();
$userinfomodel = new UserInfoModel();

$option = $_GET['OPTION'];

if( strcmp($option, 'CREATE') == 0){
    createAccount();
}
else if( strcmp($option, 'LOGIN') == 0){
    loginAccount();
}
else if( strcmp($option, 'LOGOUT') == 0){
    logoutAccount();
}
else if( strcmp($option, 'SENDEMAILVERIFY') == 0){

}
else{
    die( json_encode(array('STATE'=>false, 'ERROR'=>'wrong_option')) );
}

function createAccount(){
    global $accountmodel;
    $accid = $_POST['ACCID'];
    $pwd = $_POST['PWD'];
    $email = $_POST['EMAIL'];

    if( $accid == '' or $pwd == '' or $email == ''){
        die( json_encode(array('STATE' => false, 'ERROR'=>'empty_input_fail')) );
    }

    $value_contenter = array('EMAIL'=>$email, 'FNAME'=>'', 'SNAME'=>'', 'NICKNAME'=>'');
    $id = $accountmodel->insert($accid, $pwd, $value_contenter);

    if( $id === FALSE){
        die( json_encode(array('STATE' => false, 'ERROR'=>'insert_account_fail')) );
    }else{
        echo json_encode(array('STATE' => true));
    }

    $accountmodel->sendEmailVerify($id);
}

function loginAccount(){
    global $accountmodel;
    global $userinfomodel;
    $accid = $_POST['ACCID'];
    $pwd = $_POST['PWD'];

    if( $accid == '' or $pwd == ''){
        die( json_encode(array('STATE' => false, 'ERROR'=>'empty_input_fail')) );
    }

    $account = $accountmodel->verify($accid, $pwd);

    if( $account === FALSE){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'verify_fail')) );
    }

    $userinfoid = $account['USERINFOID'];
    $userinfo = $userinfomodel->select($userinfoid);

    if( $userinfo === FALSE ){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'select_userinfo_fail')) );
    }

    $isauthorizeemail = $userinfo['ISAUTHORIZEEMAIL'];

    if( $isauthorizeemail == FALSE ){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'not_authorize_email')) );
    }

    $_SESSION['ACCOUNTID'] = $account['ID'];
    $_SESSION['USERINFOID'] = $account['USERINFOID'];
    echo json_encode(array('STATE'=>true));
}

function logoutAccount(){
    session_destroy();
}

function sendEmailVerify(){
    global $accountmodel;
    global $userinfomodel;
    $accid = $_POST['ACCID'];
    $pwd = $_POST['PWD'];

    if( $accid == '' or $pwd == ''){
        die( json_encode(array('STATE' => false, 'ERROR'=>'empty_input_fail')) );
    }

    $account = $accountmodel->verify($accid, $pwd);

    if( $account === FALSE){
        die( json_encode(array('STATE'=>false, 'ERROR'=>'verify_fail')) );
    }

    $accountmodel->sendEmailVerify($account['ID']);
    echo json_encode(array('STATE'=>true));
}

?>