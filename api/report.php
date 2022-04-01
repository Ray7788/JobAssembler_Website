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
$user->get_user();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["JobID"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}

$jobID = $_REQUEST["JobID"];

if (is_numeric($_REQUEST["JobID"])) {
    $jobID = intval($_REQUEST["JobID"]);
    if ($jobID <= 0) {
        ApiResponseGenerator::generate_error_json(400, "Invalid job ID provided.");
    }
}
else {
    ApiResponseGenerator::generate_error_json(400, "Invalid job ID provided.");
}


try {
    $pdo = Database::connect();
    $query = "INSERT INTO Reports (JobID, ReporterID) VALUES (:jobid, :userid)";
    $statement = $pdo->prepare($query);
    $result = $statement->execute([
        "jobid" => $jobID,
        "userid" => $user->user_id
    ]);
    if ($result) {
        ApiResponseGenerator::generate_response_json(200, ["message" => "Successfully reported job posting."]);
    }
    else {
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
    }

}
catch (Throwable $exception) {
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}
