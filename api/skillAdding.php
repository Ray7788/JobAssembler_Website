<?php

require_once(__DIR__ . "/../classes/database.php");
require_once(__DIR__ . "/../classes/api_response_generator.php");
require_once(__DIR__ . "/../classes/skills.php");

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    ApiResponseGenerator::generate_error_json(405, "{$_SERVER["REQUEST_METHOD"]} method not alllowed");
}
$languages = ["java", "python", "csharp", "html", "php", "css", "cplus", "sql"];
$required_params = ["userID", "javaYears", "pythonYears", "csharpYears", "htmlYears", "phpYears", "cssYears", "cplusYears", "sqlYears", "emoIntel", "patience", "adapt", "projManage","probSolv","teamwork","interpersonal","leadership","time","decisiveness"];
foreach($required_params as $param){
    if(!isset($_REQUEST[$param])){
        ApiResponseGenerator::generate_error_json(400, "Parameter $param not set.");
    }
}
$userID = $_REQUEST["userID"];
$yearArray = array();
for($x=0; $x<count($languages); $x++){
    array_push($yearArray, $_REQUEST[$required_params[$x+1]]);
}
//emoIntel, patience, adapt, projManage, probSolv, teamwork, interpersonal, leadership, time, decisiveness
$softSkillArray = array();
for($x=count($languages)+1; $x<count($required_params); $x++){
    if($_REQUEST[$required_params[$x]] == 'true'){
        array_push($softSkillArray, $required_params[$x]);
    }
}

//When the user submits the skills page, the skills already in UserSkills for that user need to be deleted.
try{
    $result = skills::deleteSkillsForUser($userID);
    if(!$result){
        ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
    }else{
        $result = skills::insertSkillsForUser($yearArray, $userID);
        if(!$result){
            ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later");
        }else{
            if(!count($softSkillArray) == 0){
                $result = skills::insertSoftSkills($softSkillArray, $userID);
                if(!$result){
                    ApiResponseGenerator::generate_error_json(500, "There was an error with the database. Please try again later.");
                }else{
                    ApiResponseGenerator::generate_response_json(201, ["message" => "Successfully inserted skills."]);
                }   
            }            
        }
    }
}catch(Exception $exception){
    ApiResponseGenerator::generate_error_json(500, "There was an error with the databse. {$exception->getMessage()} Please try again later.");
}