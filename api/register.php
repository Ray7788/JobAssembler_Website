<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/user.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["username", "password", "forename", "surname", "accountType"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];
$forename = $_REQUEST["forename"];
$surname = $_REQUEST["surname"];
$employer = $_REQUEST["accountType"] == "employer";

#Username must be 6-30 alphanumeric characters
if (!preg_match('/^[A-Za-z\d\-]{6,30}$/', $username)) {
    ApiResponseGenerator::generate_error_json(400, "Invalid username given. Must be 6-30 alphanumeric characters.");
}
#Password must be greater than 8 characters long
if (strlen($password) < 8) {
    ApiResponseGenerator::generate_error_json(400, "Invalid password given. Must be at least 8 characters long.");
}
#Password must be fewer than 1024 characters long
if (strlen($password) > 1024) {
    ApiResponseGenerator::generate_error_json(400, "Invalid password given. Must not be more than 1024 characters long.");
}
#Forename and surname must be at least one character long
if (strlen($forename) < 1 || strlen($surname) < 1) {
    ApiResponseGenerator::generate_error_json(400, "Invalid name given. Both a forename and surname must be given.");
}
#Forenames and surnames must be fewer than 255 bytes long
if (strlen($forename) > 255){
    ApiResponseGenerator::generate_error_json(400, "Invalid forename given. Forename not be more than 63 characters long.");
}
if (strlen($surname) > 255){
    ApiResponseGenerator::generate_error_json(400, "Invalid surname given. Surname not be more than 63 characters long.");
}
#Username must not already be taken.
if (User::check_username_exists($username)) {
    ApiResponseGenerator::generate_error_json(400, "Invalid username given. Username is already in use, please choose another.");
}
try {
    $result = User::create_user($username, $password, $forename, $surname, $employer);
    if (!$result) {
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
    }
    else {
        $userID = User::get_user_id($username);
        ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully created user.", "id" => $userID]);
    }
} catch (Exception $exception) {
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}