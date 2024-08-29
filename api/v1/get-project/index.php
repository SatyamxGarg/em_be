<?php

include "../../../cors.php";

try {
    header('Content-type: application/json');

    require_once('../../../vendor/autoload.php');
    include "../../../verify_token.php";
    include '../../../checkMethod.php';
    include '../../../connect.php';
    checkMethod("GET");

    $p_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;

 
    if (!isset($p_id)) {
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Bad request",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $id = $userData->id;

   
    $sql1 = "select * from em_projects WHERE project_id=$p_id";
    $result1 = mysqli_query($con, $sql1);
    $row=mysqli_fetch_array($result1);

    if (!$result1) {
        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "Failed to Fetch Project. Please try again.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $server_response_success = array(
        "status" => true,
        "message" => "Project successfully Fetched.",
        "data" => [$row]
    );
    echo json_encode($server_response_success);

} catch (Exception $ex) {
    http_response_code(500);
    $server_response_error = array(
        "status" => false,
        "message" => $ex->getMessage()
    );
    echo json_encode($server_response_error);
}
