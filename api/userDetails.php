<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/user.php");

session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
$user = $_SESSION["user"];
if (!$user->is_authenticated()) {
    ApiResponseGenerator::generate_error_json(401, "User not logged in.");
}
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
$user = new User();
try {
    $result = $user->get_user($id);
    ApiResponseGenerator::generate_response_json(200, $result);

}
catch (Throwable $exception) {
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()}. Please try again later.");
}