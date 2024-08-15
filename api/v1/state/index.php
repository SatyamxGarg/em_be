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
       
        
        if (empty($inputData['user_country'])) {
            $data = [
                'status' => false,
                "message" => "Country is required",
                "data"=> []
            ];
            http_response_code(404);
            echo json_encode($data);
            die();
        }
        $country_name = $inputData['user_country'];

            $sql = "SELECT country_id FROM em_countries WHERE country_name='$country_name'";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) <= 0) {
                $data = [
                    "status" => false,
                    "message" => "Country Not Found",
                    "data" => []
                ];
                http_response_code(404);
                echo json_encode($data);
            die();
            }
                $row = mysqli_fetch_assoc($res);
                $country_id = $row['country_id'];
                $fetch_state = "SELECT state_id,state_name FROM em_states WHERE country_id='$country_id'";
                $fetch_state_execute = mysqli_query($con, $fetch_state);
                $value = mysqli_fetch_all($fetch_state_execute, MYSQLI_ASSOC);
                $data = [
                    "status" => true,
                    "message" => "States fetched Successfully",
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

