<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/userjobs.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");

header("Access-Control-Allow-Origin: *");   //TODO, must revert before complete
if($_SERVER["REQUEST_METHOD"] !== "POST"){
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method now allowed");
}


$required_params = ["userAccepted", "userID", "jobID"];
foreach($required_params as $param){
    if(!isset($_REQUEST[$param])){
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set");
    }
}


$userID = $_REQUEST["userID"];
$jobID = $_REQUEST["jobID"];
$userAccepted = $_REQUEST["userAccepted"];

try{
    $result = UserJob::update_employee($userAccepted, $userID, $jobID);
    if(!$result){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
    }else{
        
        ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully updated database."]);
    }
}
catch(Exception $exception){
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}

?>