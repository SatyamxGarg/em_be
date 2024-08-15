<?php

require '../../../connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../../cors.php';
require_once('../../../vendor/autoload.php');
require_once('../../../verify_token.php');
include '../../../connect.php';

try {

    header('Content-type: application/json');
    include '../../../checkMethod.php';
    checkMethod("POST");
    
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (empty($inputData['user_state'])) {
            $data = [
                'status' => false,
                "message" => "State is required",
                "data"=> []
            ];
            http_response_code(404);
            echo json_encode($data);
            die();
           
        } 
        $state_name = $inputData['user_state'];
            
            $sql = "SELECT state_id FROM em_states WHERE state_name='$state_name'";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) <= 0) {
                $data = [
                    "status" => false,
                    "message" => "State Not Found",
                    "data" => []
                ];
                http_response_code(404);
                echo json_encode($data);
                die();
            }

                $row = mysqli_fetch_assoc($res);
                $state_id = $row['state_id'];
                $fetch_city = "SELECT city_id,city_name FROM em_cities WHERE state_id='$state_id'";
                $fetch_city_execute = mysqli_query($con, $fetch_city);
                $value = mysqli_fetch_all($fetch_city_execute, MYSQLI_ASSOC);
                $data = [
                    "status" => true,
                    "message" => "Cities fetched Successfully",
                    "data" => [$value]
                ];
                http_response_code(200);
                echo json_encode($data);
           
    
} catch (Exception $ex) {
    http_response_code(500);
    $server_response_error = array(
        "status" => false,
        "message" => $ex->getMessage(),
        "data" => []
    );
    echo json_encode($server_response_error);
}
?>


