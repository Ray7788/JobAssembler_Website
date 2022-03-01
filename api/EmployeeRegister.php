<?php
require_once("../classes/database.php");
require_once("../classes/api_response_generator.php");
require_once("../classes/employee.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");

$required_params["email","biography","CV","profilePic"];
foreach($required_params as $param){
	if(!issit($_REQUEST[$param])){
		ApiResponseGenerator::generate_error_json(400,"Not parameter at $param");
	}
}

$email = $_REQUEST['email'];
$biography = $_REQUEST['biography'];
$cv = $_REQUEST['CV'];
$profilePic = $_REQUEST['profilePic']

#Email verification

if (!preg_match('^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$', $email)){
	ApiResponseGenerator::generate_error_json(400,"Invalid email given"))
}

try{
	$result = employee::create_employee($biography);
	
}