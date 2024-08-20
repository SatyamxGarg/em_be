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


    $inputData = json_decode(file_get_contents("php://input"), true);

    if (
        empty($inputData['user_id'])
    ) {

        $data = [
            'status' => false,
            "message" => "Id is required",
            "data"=> []
        ];
        http_response_code(404);
        echo json_encode($data);
        die();
    }
    $id = $inputData['user_id'];

    $sql = "SELECT * FROM em_users WHERE user_id='$id'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) <= 0) {
        $data = [
            "status" => false,
            "message" => "User Not Found",
            "data" => []
        ];
        http_response_code(404);
        echo json_encode($data);
        die();
    }

    $row = mysqli_fetch_assoc($res);
   
    $value = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $data = [
        "status" => true,
        "message" => "User fetched Successfully",
        "data" => [$row]
    ];
    http_response_code(200);
    echo json_encode($data);

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