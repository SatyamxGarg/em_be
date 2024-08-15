<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con=new mysqli('localhost','root','password','employee_management');
if(!$con){
    die(mysqli_error($con));
}
?>