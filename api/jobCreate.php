<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/user.php");
require_once(__DIR__ . "/../classes/job.php");

session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if (!$user->is_authenticated()) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
if ($user->company_id < 1) {
    ApiResponseGenerator::generate_error_json(403, "User not assigned to company.");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["title", "description", "remote"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}    

$title = $_REQUEST["title"];
$description = $_REQUEST["description"];
$remote = $_REQUEST["remote"] == "remote";

if (isset($_REQUEST["latitude"]) && isset($_REQUEST["longitude"]) && $_REQUEST["latitude"] != "" && $_REQUEST["longitude"] != "") {
    $latitude = floatval($_REQUEST["latitude"]);
    $longitude = floatval($_REQUEST["longitude"]);
}
else {
    $latitude = null;
    $longitude = null;
}

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
    $result = Job::create_job($title, $description, $user->company_id, $remote, $latitude, $longitude);
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
