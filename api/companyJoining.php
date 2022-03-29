<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/companyJoinRequest.php");

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not allowed");
}
$required_params = ["companyID", "userID"];
foreach($required_params as $param){
    if(!isset($_REQUEST[$param])){
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$companyID = $_REQUEST['companyID'];
$userID = $_REQUEST['userID'];

//There can't already be a company join request for this companyID and userID
if(companyJoinRequest::checkIfRequestExists($companyID, $userID)){
    ApiResponseGenerator::generate_error_json(400, "You have already sent a join request for this company. Please ask your company to accept your account.");
}

try{
    $result = companyJoinRequest::createJoinRequest($companyID, $userID);
    if(!$result){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
    }else{
        ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully made join request."]);
    }
}catch(Exception $exception){
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");

}

