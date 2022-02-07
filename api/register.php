<?php
#require_once("classes/database.php");
header("Content-Type: application/json; charset=UTF-8");
$database = new Database();
$connection = $database->connect();