<?php
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

    $id=$userData->id;

    $sql = "SELECT * FROM em_projects where project_user_id=$id";
    $result = mysqli_query($con, $sql);

    $users = [];

   
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    if (!empty($users)) {
        $data = [
            "status" => true,
            "message" => "List found",
            "data" => [$users]
        ];
        http_response_code(200);
        echo json_encode($data); 
    } else {
        $data = [
            "status" => false,
            "message" => "No List found",
            "data" => []
        ];
        http_response_code(404);
        echo json_encode($data); 
    }

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