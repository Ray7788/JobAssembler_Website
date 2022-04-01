<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/job.php");
require_once(__DIR__ . "/../classes/user.php");

if (!in_array($_SERVER["REQUEST_METHOD"], ["DELETE", "POST"])) {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    var_dump($_SESSION);
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if (!$user->is_authenticated()) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
$user->get_user();
if ($user->company_id < 1) {
    ApiResponseGenerator::generate_error_json(403, "User not assigned to company.");
}

$required_params = ["jobID"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$id = intval($_REQUEST["jobID"]);
$job = new Job();
try {
    $job->get_job($id);
    if ($job->company->id != $user->company_id) {
        ApiResponseGenerator::generate_error_json(403, "User not assigned to job's company.");
    }
    $result = $job->delete_job();
    if (!$result) {
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
    }
    else {
        ApiResponseGenerator::generate_response_json(200, ["message" => "Successfully created job."]);
    }

}
catch (Throwable $exception) {
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}