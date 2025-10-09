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
        empty($data['user_first_name']) || empty($data['user_last_name']) || empty($data['user_age']) || empty($data['user_phone']) || empty($data['user_gender']) || empty($data['user_country']) || empty($data['user_state']) || empty($data['user_city'])
    ) {

        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Invalid details",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

        $user_first_name=$data['user_first_name'];
        $user_last_name=$data['user_last_name'];
  
          $user_age=$data['user_age'];
          $user_gender=$data['user_gender'];
          $user_phone=$data['user_phone'];
          $user_country=$data['user_country'];
          $user_state=$data['user_state'];
          $user_city=$data['user_city'];
      
    $id=$userData->id;
    $sql="update em_users set user_first_name = '$user_first_name' , user_last_name = '$user_last_name' , user_age='$user_age' , user_gender='$user_gender' ,user_phone='$user_phone' ,user_country='$user_country' ,user_state='$user_state', user_city='$user_city' where user_id=$id";
    $result=mysqli_query($con,$sql);
    
    if($result){
    $data=[
        "status"=>true,
        "message"=>"User detials updated.",
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