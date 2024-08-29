<?php

include "../../../cors.php";

try {
    header('Content-type: application/json');

    require_once('../../../vendor/autoload.php');
    include "../../../verify_token.php";
    include '../../../checkMethod.php';
    include '../../../connect.php';
    checkMethod("DELETE");

    $p_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;

    if (empty($p_id)) {
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

    $sql = "SELECT project_id FROM em_projects WHERE project_id=$p_id";
    $res = mysqli_query($con, $sql);
    $row = mysqli_num_rows($res);
    if ($row == 0) {
        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "Project doesn't exist.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $sql1 = "DELETE FROM em_projects WHERE project_id=$p_id AND project_user_id=$id";
    $result1 = mysqli_query($con, $sql1);

    if (!$result1) {
        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "Failed to delete Project. Please try again.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $server_response_success = array(
        "status" => true,
        "message" => "Project successfully deleted.",
        "data" => [$result1]
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
