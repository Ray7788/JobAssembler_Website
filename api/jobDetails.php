<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/job.php");
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["id"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$id = intval($_REQUEST["id"]);
$job = new Job();
try {
    $result = $job->get_job($id);
    ApiResponseGenerator::generate_response_json(200, $result);

}
catch (Throwable $exception) {
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}