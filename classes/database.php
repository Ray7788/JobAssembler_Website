<?php
require_once("../config.ini.php");
if (!isset($database_host) || !isset($database_user) || !isset($database_pass) || !isset($group_dbnames)){
    http_response_code(500);
    echo(json_encode(array("message" => "There was an error with the website configuration. Please try again later.")));
    die(1);
} elseif (!in_array(Database::$database_name, $group_dbnames)){
    http_response_code(500);
    echo(json_encode(array("message" => "There was an error with the website configuration. Please try again later.")));
    die(1);
}
class Database {
    public static string $database_name = "2021_comp10120_x17";
    public PDO $pdo;

    function connect(): PDO {
        global $database_host;
        global $database_user;
        global $database_pass;
        $database_name = self::$database_name;
        try {
            $this->pdo = new PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_pass);
        } catch (PDOException $exception) {http_response_code(500);
            echo(json_encode(array("message" => "There was an error connecting to the database. {$exception->errorInfo}. Please try again later.")));
            die(1);
        }
        return $this->pdo;
    }
}