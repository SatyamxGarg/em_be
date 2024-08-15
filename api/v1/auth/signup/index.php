 <?php

include "../../../../cors.php";

try {
    header('Content-type: application/json');

    include '../../../../checkMethod.php';
    checkMethod("POST");

    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);


    if (
        empty($data['user_first_name']) || empty($data['user_last_name']) || empty($data['user_email'])
        || empty($data['user_password'])
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

    

    $user_first_name = $data['user_first_name'];
    $user_last_name = $data['user_last_name'];
    // $user_age = $data['user_age'];// -->

    // $user_phone = $data['user_phone'];
    // $pattern = '/^[0-9]{10}+$/';
    //   if (!preg_match($pattern, $user_phone)) {
    //     http_response_code(400);
    //     $server_response_error = array(
    //         "status" => false,
    //         "message" => "Mobile Number is invalid",
    //         "data" => []
    //     );
    //     echo json_encode($server_response_error);
    //     die();      } 

    $user_email = $data['user_email'];
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "E-mail is invalid",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
      } 

    // $user_gender = $data['user_gender'];

    $user_password = $data['user_password'];
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/';
    if (!preg_match($pattern, $user_password)) {
        http_response_code(400);
        $server_response_error = array(
            "status" => false,
            "message" => "Password is invalid",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }
    require '../../../../connect.php';
    // check for duplicate user 
    // here I am check for user email id for the same 
    $sql = "SELECT * FROM `em_users` WHERE user_email='$user_email';";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) > 0) {
        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "This user is already registered.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }
    // insert/add new user details
    // encrypt user password 
    $password_hash = md5($user_password);

    

    // insert data into the database
    $sql1 = "INSERT INTO `em_users`(`user_first_name`,`user_last_name`,`user_email`,`user_password`) VALUES('$user_first_name','$user_last_name','$user_email','$password_hash')";
    $result1 = mysqli_query($con, $sql1);
    if (!$result1) {

        http_response_code(404);
        $server_response_error = array(
            "status" => false,
            "message" => "Failed to create user. Please try again.",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }

    // $row1 = mysqli_fetch_array($result1);
    $server_response_success = array(
        "status" => true,
        "message" => "User successfully created.",
        "data" => []
    );
    echo json_encode($server_response_success);
} catch (Exception $ex) {
    http_response_code(500);
    $server_response_error = array(
        "status" => false,
        "message" =>  $ex->getMessage()
    );
    echo json_encode($server_response_error);
} // end of try/catch
