<?php

require_once("prevent_direct_access.php");
require_once("database.php");

class employee
{
	public int $id;
	public String $email;
	public String $biography;

	public static function create_employee(String $biography){
		$pdo = Database::connect();
		$query = "INSERT INTO 'UserAccounts'('Biography') VALUES(:biography)";
		$statement = $pdo->prepare($query);
		return $statement->execute([
			"biography" => $biography
		]);
	}
}