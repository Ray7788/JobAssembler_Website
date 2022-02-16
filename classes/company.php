<?php
require_once("prevent_direct_access.php");
require_once("database.php");

class company
{
    public int $id;
    public string $name;
    public string $description;
    public string $image_url;

    public static function get_company_id(string $name): int {
        $pdo = Database::connect();
        $query = "SELECT `CompanyID` FROM `Companies` WHERE Name = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$name]);
        $result = $statement->fetch();
        if ($result == false) {
            return -1;
        }
        else {
            return intval($result[0]);
        }
    }

    public function get_details(int $id = null): void {
        if (isset($id)){
            $this->id = $id;
        }
        $pdo = Database::connect();
        $query = "SELECT * FROM `UserAccounts` WHERE UserID = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$this->id]);
        $result = $statement->fetch();
        $this->name = $result["Name"];
        $this->description = $result["Description"];
        $this->image_url = $result["CompanyImage"];
    }

    public static function check_company_exists(string $name){
        $pdo = Database::connect();
        if(strlen($name)==0) return true;
        $query = "SELECT COUNT(*) FROM Companies WHERE Username = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$name]);
        $count = intval($statement->fetch()[0]);
        return $count > 0;
    }

    public static function create_company(string $name, string $description): bool{
        $pdo = Database::connect();
        $query = "INSERT INTO `Companies` (`Name`, `Description`) VALUES (:name, :description)";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "name" => $name,
            "description" => $description
        ]);

    }
}