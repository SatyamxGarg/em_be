<?php
function checkMethod($method) {
    if ($_SERVER['REQUEST_METHOD'] != $method) {
    
        http_response_code(405);
        $server_response_error = array(
            "status" => false,
            "message" => "Method not allowed",
            "data" => []
        );
        echo json_encode($server_response_error);
        die();
    }
}
?>
