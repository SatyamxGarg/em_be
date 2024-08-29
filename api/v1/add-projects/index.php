<?php

include "../../../cors.php";

try {

    header('Content-type: application/json');
require_once('../../../vendor/autoload.php');
require_once('../../../verify_token.php');
    include '../../../checkMethod.php';
    include '../../../connect.php';

    checkMethod("POST");


    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);



    if (
        empty($data['project_name']) || empty($data['project_description']) // || empty($data['project_tech']) || empty($data['project_status']) || empty($data['project_lead']) || empty($data['project_manager']) || empty($data['project_client']) || empty($data['management_tool'])
    //    || empty($data['management_url']) || empty($data['repo_tool']) || empty($data['repo_url'])

    ) {
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Bad request",
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

    $id = $userData->id;
    
    // insert data into the database
    $sql1 = "INSERT INTO `em_projects`(`project_user_id`,`project_name`,`project_description`,`project_tech`,`project_status`,`project_lead`,`project_manager`,`project_client`,`management_tool`
    ,`management_url`,`repo_tool`,`repo_url`,`project_startDate`,`project_deadlineDate`) VALUES('$id','$project_name','$project_description','$project_tech','$project_status',
    '$project_lead','$project_manager','$project_client','$management_tool','$management_url','$repo_tool','$repo_url','$project_startDate','$project_deadlineDate')";
    $result1 = mysqli_query($con, $sql1);


    if (!$result1) {

        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "Failed to add Project. Please try again.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    $server_response_success = array(
        "status" => true,
        "message" => "Project Successfully added.",
        "data" => [$result1]
    );
    echo json_encode($server_response_success);

} catch (Exception $ex) {
    http_response_code(500);
    $server_response_error = array(
        "status" => false,
        "message" =>  $ex->getMessage()
    );
    echo json_encode($server_response_error);
}
