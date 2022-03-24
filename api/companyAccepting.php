<?php
require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/companyJoinRequest.php");
header("Access-Control-Allow-Origin: *");

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

try{
    $result = companyJoinRequest::acceptJoinRequest($companyID, $userID);
    if(!$result){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
    }else{
        $result = companyJoinRequest::changeCompanyID($companyID, $userID);
        if(!$result){
            ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
        }else{
            ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully accepted join request."]);
        }  
    }
}catch(Exception $exception){
    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. {$exception->getMessage()} Please try again later.");
}