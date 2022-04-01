<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/userjobs.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/user.php");
require_once(__DIR__ . "/../classes/job.php");

session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
$user = $_SESSION["user"];
if (!$user->is_authenticated()) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
if($_SERVER["REQUEST_METHOD"] !== "POST"){
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}


$required_params = ["userID", "jobID"];
foreach($required_params as $param){
    if(!isset($_REQUEST[$param]) || $_REQUEST[$param] == ""){
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set");
    }
}


$userID = $_REQUEST["userID"];
$jobID = $_REQUEST["jobID"];

if (isset($_REQUEST["userAccepted"])) {
    if ($userID != $user->user_id) {
        ApiResponseGenerator::generate_error_json(403, "User not authorised to change acceptance for this job.");
    }
    try {
        $result = UserJob::update_employee($_REQUEST["userAccepted"], $userID, $jobID);
        if(!$result){
            ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
        }
    }
    catch(Exception $exception){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
    }
}
if (isset($_REQUEST["companyAccepted"])) {
    try {
        $job = new Job;
        $job->get_job($jobID);
        if ($job->company->id != $user->company_id) {
            ApiResponseGenerator::generate_error_json(403, "User not authorised to change acceptance for this job.");
        }
        $result = UserJob::update_employer($_REQUEST["companyAccepted"], $userID, $jobID);
        if(!$result){
            ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
        }
    }
    catch(Exception $exception){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
    }
}
ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully updated database."]);
?>