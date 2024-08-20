<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

// use Firebase\JWT\JWT;
// use Firebase\JWT\KEY;
include '../../../cors.php';
require_once('../../../vendor/autoload.php');
require_once('../../../verify_token.php');
include '../../../connect.php';

try { 
    header('Content-type: application/json');
    include '../../../checkMethod.php';
    checkMethod("PUT");

    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);
    if (
        empty($data['currentPassword']) || empty($data['newPassword'])
    ) {
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Password fields can't be empty",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }
        $current_password=md5($data['currentPassword']);
        $new_password=$data['newPassword'];
        // $user_password=md5($data['user_password']);

    // $jwt_token=checkAuthorization();
    // $id=$jwt_token->{'id'};
    $id=$userData->id;
    $sql="select user_password from em_users where user_id=$id";
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_array($result);
    $db_password=$row['user_password'];
    if($db_password!=$current_password){
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Current password doesn't match.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/';
    if (!preg_match($pattern, $new_password)) {
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "New password is invalid",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }
    $new_password=md5($new_password);
    $sql="update em_users set user_password= '$new_password' where user_id=$id";
    $result=mysqli_query($con,$sql);
    if($result){
    $data=[
        "status"=>true,
        "message"=>"Password Successfully Updated.",
        "data"=>[$result]
    ];
    http_response_code(201);
    echo json_encode($data);
}
}
catch (Exception $ex) {
    http_response_code(500);
    $server_response_error = array(
        "status" => false,
        "message" => $ex->getMessage(),
        "data" => []
    );
    echo json_encode($server_response_error);
}
?>