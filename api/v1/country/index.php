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
    checkMethod("GET");

        $sql = "SELECT country_name,country_id FROM em_countries";
        $res = mysqli_query($con, $sql);
       
     
        $row = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $data = [
            "status" => true,
            "message" => "Countries Fetched Successfully",
            "data"=>[$row]
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