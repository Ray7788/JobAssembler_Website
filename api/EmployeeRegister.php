<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/employee.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["email","biography","CV","profilePic"];
foreach($required_params as $param){
	if(!isset($_REQUEST[$param])){
		ApiResponseGenerator::generate_error_json(400,"Not parameter at $param");
	}
}

$email = $_REQUEST['email'];
$biography = $_REQUEST['biography'];
$cv = $_REQUEST['CV'];
$profilePic = $_REQUEST['profilePic'];

#Email verification

// Currently allows used emails
if (!preg_match('^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$', $email)){
	ApiResponseGenerator::generate_error_json(400,"Invalid email given");
}

try{
	$result = employee::create_employee($biography);
    if(!$result){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
    }
    else{
        ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully created user."]);
    }
}catch(Exception $exception){
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}

