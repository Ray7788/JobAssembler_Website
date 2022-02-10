<?php
require_once("../classes/database.php");
require_once("../classes/api_response_generator.php");
require_once("../classes/user.php");
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["username", "password"];
foreach ($required_params as $param) {
    if (!isset($_REQUEST[$param])) {
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

#Username must exist in the database to continue.
if (!User::check_username_exists($username)) {
    ApiResponseGenerator::generate_error_json(403, "The username or password provided was incorrect. (Username incorrect)");
}
$id = User::get_user_id($username);
#If the above check passes, $id should never be less than 0, but it can't hurt to check anyway.
if ($id < 0) {
    ApiResponseGenerator::generate_error_json(500, "An error occurred while logging in. Please try again later.");
}
$user = new User();
$success = $user->authenticate($id, $password);
if ($success) {
    session_set_cookie_params(0, httponly: true);
    session_start();
    if (isset($_SESSION["user"])) {
        if ($_SESSION["user"] instanceof User){
            $_SESSION["user"]->revoke_auth();
        }
    }
    session_regenerate_id(true);
    $_SESSION["user"] = $user;
    ApiResponseGenerator::generate_response_json(200, [
        "message" => "Login successful."
    ]);
}
else {
    ApiResponseGenerator::generate_error_json(403, "The username or password provided was incorrect. (Password incorrect)");
}
