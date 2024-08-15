<?php

include "../../../../cors.php";

use Firebase\JWT\JWT;
require_once('../../../../vendor/autoload.php');


try {
    
    header('Content-type: application/json');
    
    include '../../../../checkMethod.php';
    checkMethod("POST");

    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);

    if (empty($data['user_email']) || empty($data['user_password'])) {

        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Bad request",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }


    $user_email = $data['user_email'];
    $user_password = $data['user_password'];

    require '../../../../connect.php';
    // checking for valid user details 
    $sql = "SELECT * FROM `em_users` WHERE user_email='$user_email'";
    $result = mysqli_query($con, $sql);

    // Check if user not exists.
    if (mysqli_num_rows($result) == 0) {
        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "Email not registered",
            "data" => ""
        );
        echo json_encode($server_response_error);
        die();
    }


    $row = mysqli_fetch_array($result);

    if (! ($row['user_password'] === md5($user_password)) ) {
        http_response_code(401);
        $server_response_error = array(
            "status" => false,
            "message" => "Incorrect password"
        );
        echo json_encode($server_response_error);
        die();
    }

    $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    
    $data = [
        'userName' => $row['user_first_name'],
        "exp" => time() + 10000,
        "id" => $row['user_id'],
        "email"=>$row['user_email']
    ];
    
    $token = JWT::encode(
        $data,
        $secretKey,
        'HS512'
    );

    $response = array(
        "token" => $token,
    );
     

    http_response_code(200);
    $server_response_success = array(
        "status" => true,
        "message" => "Success",
        "data" => $response
    );

    echo json_encode($server_response_success);
} catch (Exception $ex) {
    http_response_code(500);
    $server_response_error = array(
        "status" => false,
        "message" => $ex->getMessage(),
        "data" => []
    );
    echo json_encode($server_response_error);
}
