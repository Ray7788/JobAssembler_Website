<?php
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/user.php");
if (!in_array($_SERVER["REQUEST_METHOD"], ["GET", "POST"])) {
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
session_start();
if (isset($_SESSION["user"])) {
    $_SESSION["user"]->revoke_auth();
}
session_regenerate_id(true);
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("Location: /JobAssembler/index.php");
}
else {
    ApiResponseGenerator::generate_response_json(200, ["message" => "Successfully logged out."]);
}