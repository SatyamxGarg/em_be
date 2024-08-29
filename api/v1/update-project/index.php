<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

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

     $p_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;


    if (
        empty($data['project_name']) || empty($data['project_description']) || empty($data['project_tech']) || empty($data['project_status']) || empty($data['project_lead']) || empty($data['project_manager']) || empty($data['project_client']) || empty($data['management_tool'])
        || empty($data['management_url']) || empty($data['repo_tool']) || empty($data['repo_url'])){

        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Invalid details",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $project_name = $data['project_name'];
    $project_description = $data['project_description'];
    $project_tech = $data['project_tech'];
    $project_status = $data['project_status'];
    $project_lead = $data['project_lead'];
    $project_manager = $data['project_manager'];
    $project_client = $data['project_client'];
    $management_tool = $data['management_tool'];
    $management_url = $data['management_url'];
    $repo_tool = $data['repo_tool'];
    $repo_url = $data['repo_url'];
    $project_startDate=$data['project_startDate'];
    $project_deadlineDate=$data['project_deadlineDate'];


    $id=$userData->id;
    $sql="update em_projects set project_name = '$project_name', project_description = '$project_description', project_tech = '$project_tech', project_status = '$project_status', project_lead = '$project_lead', project_manager = '$project_manager', project_client = '$project_client', management_tool = '$management_tool', management_url = '$management_url', repo_tool = '$repo_tool', repo_url = '$repo_url', project_startDate='$project_startDate', project_deadlineDate='$project_deadlineDate' WHERE project_id = '$p_id' ";

    $result=mysqli_query($con,$sql);

    if($result){
    $data=[
        "status"=>true,
        "message"=>"Project detials updated.",
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