<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('vendor/autoload.php');
header('Content-type: application/json');

function checkAuthorization(){
    $headers=apache_request_headers();
    if(!isset($headers['Authorization']) || empty($headers['Authorization'])){
        $response=[
            'status' =>false,
            'message' =>"No Authorization",
            'data' =>[]
        ];

        http_response_code(401);
        echo json_encode($response);
        return false;
    }
    $token=$headers['Authorization'];
    return validateToken($token);
}

function validateToken($token){
    $secretkey="bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    try{
        $decoded=JWT::decode($token,new Key($secretkey,'HS512'));
        $currentTime=time();
        if($decoded->exp < $currentTime){
            $response=[
                'status' =>false,
                'message' =>"session expired",
                'data' =>[]
            ];
            http_response_code(401);
            echo json_encode($response);
            return false;
        }
        return $decoded;
    }
    catch(Exception $e){
        $response=[
            'status' =>false,
             'message' =>"Unauthorization",
             'data' =>[]
        ];
        http_response_code(500);
        echo json_encode($response);
        return false;
    }
}
 $userData= checkAuthorization();
 if(!$userData){
die();
 }
?>