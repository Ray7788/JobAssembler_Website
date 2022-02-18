<?php
require_once("../classes/database.php");
require_once("../classes/api_response_generator.php");
require_once("../classes/user.php");
require_once("../classes/job.php");
header("Access-Control-Allow-Origin: *"); #TODO - MUST REVERT BEFORE COMPLETE
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["title", "description", "location"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}    

$title = $_REQUEST["title"];
$description = $_REQUEST["description"];
$location = $_REQUEST["location"];


if (strlen($title) < 3){
    ApiResponseGenerator::generate_error_json(400, "Invalid job title. Must be longer than 3 characters");
}
if (strlen($title) > 65535) {
    ApiResponseGenerator::generate_error_json(400, "Invalid job title. Title is too long");
}
if (strlen($description) < 50){
    ApiResponseGenerator::generate_error_json(400, "Invalid job description. Must be longer than 50 characters");
}
if (strlen($description) > 4294967295){
    ApiResponseGenerator::generate_error_json(400, "Invalid job description. Description is too long");
}
try {
    $result = Job::create_job($title, $description, $location, 1);
    if (!$result) {
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
    }
    else {
        ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully created job."]);
    }
    
}
catch (Exception $exception) {
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}
