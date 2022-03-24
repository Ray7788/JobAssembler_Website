<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
$user = $_SESSION["user"];


if(!$user->is_authenticated()){
    header("Location: index.php");
    die(0);
}

$companies = array();
$pdo = Database::connect();
//Get a list of all company IDs and names
$query = "SELECT * FROM Companies";
$statement = $pdo->prepare($query);
$statement->execute();
$companies = $statement->fetchAll();
//$companies = array_map('implode', $companies);



?>
