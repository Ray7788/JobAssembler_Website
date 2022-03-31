<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/user.php");

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
$required_params = ["email","biography", "latitude", "longitude", "remote"];
foreach($required_params as $param){
	if(!isset($_REQUEST[$param])){
		ApiResponseGenerator::generate_error_json(400,"Parameter $param not set.");
	}
}
if(!isset($_FILES["profilePic"])) {
    ApiResponseGenerator::generate_error_json(400, "Parameter profilePic not set.");
}

$email = $_REQUEST["email"];
$biography = $_REQUEST["biography"];
$latitude = $_REQUEST["latitude"];
$longitude = $_REQUEST["longitude"];
$picture = $_FILES["profilePic"];
$remote = $_REQUEST["remote"] == "remote";

$updated = [];

//Deal with remote working

$user->update_property("Remote", intval($remote));
$updated[] = "remote";

//Deal with email
if (preg_match('/\w+@.+\..+/', $email) ){
    $user->update_property("Email", $email);
    $updated[] = "email";
}
else if ($email != "") {
    ApiResponseGenerator::generate_error_json(400, "Invalid email given.");
}

//Deal with biography
if ($biography != "") {
    $user->update_property("Biography", $biography);
    $updated[] = "biography";
}

//Deal with location
if (is_numeric($latitude) && is_numeric($longitude)) {
    $lat = floatval($latitude);
    $lng = floatval($longitude);
    try {
        $user->set_location($lat, $lng);
        $updated[] = "location";
    } catch (Throwable $exception) {
        ApiResponseGenerator::generate_error_json(500, "There was an error connecting to the database. Please try again later.");
    }
}
else {
    try {
    $user->set_location(0, 0);
    $updated[] = "location";
    } catch (Throwable $exception) {
        ApiResponseGenerator::generate_error_json(500, "There was an error connecting to the database. Please try again later.");
    }
}

//Deal with profile picture
if ($picture["size"] >= 0 && $picture["error"] == UPLOAD_ERR_OK) {
    $pictureDir = __DIR__ . "/../profilePictures";
    if (!is_dir($pictureDir)) {
        mkdir($pictureDir);
    }
    $pictureInfo = pathinfo($picture["name"]);
    $picturePath = $pictureDir . "/" . $user->user_id . "." . $pictureInfo["extension"];
    $acceptedExtensions = ["png", "jpg", "jpeg"];
    if (!in_array($pictureInfo["extension"], $acceptedExtensions)) {
        unlink($picture["tmp_name"]);
        ApiResponseGenerator::generate_error_json(400, "Invalid profile picture uploaded.");
    }
    $mime = mime_content_type($picture["tmp_name"]);
    $acceptedMimes = ["image/png", "image/jpeg"];
    if (!in_array($mime, $acceptedMimes) || !in_array($picture["type"], $acceptedMimes)) {
        unlink($picture["tmp_name"]);
        ApiResponseGenerator::generate_error_json(400, "Invalid profile picture uploaded.");
    }
    $size = getimagesize($picture["tmp_name"]);
    if ($size[0] != $size[1]) {
        unlink($picture["tmp_name"]);
        ApiResponseGenerator::generate_error_json(400, "Invalid profile picture uploaded. Picture is not square.");
    }
    if ($picture["size"] > 2000000) {
        unlink($picture["tmp_name"]);
        ApiResponseGenerator::generate_error_json(400, "Invalid profile picture uploaded. Image is too large.");
    }
    if (!move_uploaded_file($picture["tmp_name"], $picturePath)) {
        unlink($picture["tmp_name"]);
        ApiResponseGenerator::generate_error_json(400, "There was an error with the profile picture, please try a different one.");
    }
    try {
        $user->update_property("ProfileImage", "profilePictures/$user->user_id.{$pictureInfo['extension']}");
        $updated[] = "ProfileImage";
    } catch (Throwable $exception) {
        ApiResponseGenerator::generate_error_json(500, "There was an error connecting to the database. Please try again later.");
    }
}
else if ($picture["error"] != UPLOAD_ERR_NO_FILE) {
    ApiResponseGenerator::generate_error_json(500, "There was an error uploading profile picture. Please try again later.");
}
ApiResponseGenerator::generate_response_json(200, ["message" => "Successfully updated profile.", "Updated" => $updated]);
