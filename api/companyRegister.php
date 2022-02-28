<?php
require_once("../classes/database.php");
require_once("../classes/api_response_generator.php");
require_once("../classes/company.php");
header("Access-Control-Allow-Origin: *"); #TODO - MUST REVERT BEFORE COMPLETE
if($_SERVER["REQUEST_METHOD"] !== "POST"){
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["name", "description"];
foreach($required_params as $param){
    if(!isset($_REQUEST[$param])){
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$name = $_REQUEST["name"];
$description = $_REQUEST["description"];


#Name must be between 3 and 64 alphanumeric characters - can also have whitespace
$pattern = "/^[A-Za-z\d\s]{3,64}$/";
if(!preg_match($pattern, $name)){
    ApiResponseGenerator::generate_error_json(400, "Invalid company name given. Must be between 3-64 alphanumeric characters." . $name);
}
#Description must be at least 20 characters
if(strlen($description) < 20){
    ApiResponseGenerator::generate_error_json(400, "Invalid description given. Must be at least 20 characters.");
}

#Company name mustn't already be taken
if(company::check_company_exists($name)){
    ApiResponseGenerator::generate_error_json(400, "Invalid company name given. Name is already in use, please choose another.");
}

try{
    $result = company::create_company($name, $description);
    if(!$result){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
    }
    else{
        ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully created user."]);
    }
}catch(Exception $exception){
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}

