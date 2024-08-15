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
    checkMethod("GET");


    // $inputData = file_get_contents('php://input');
    // $data = json_decode($inputData, true);

    // $jwt_token=checkAuthorization();
    // $id=$jwt_token->{'id'};
    $id=$userData->id;
    $sql="select * from em_users where user_id=$id";
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($result);
    if($row){
    $data=[
        "status"=>true,
        "message"=>"User found",
        "data"=>[$row]
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