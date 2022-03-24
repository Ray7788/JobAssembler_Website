<?php
require_once("prevent_direct_access.php");
require_once("database.php");

class companyJoinRequest{
    public static function checkIfRequestExists(string $companyID, string $userID){
        $pdo = Database::connect();
        $query = "SELECT COUNT(*) FROM CompanyJoinRequests WHERE CompanyID = :companyid AND UserID = :userid";
        $statement = $pdo->prepare($query);
        $statement->execute([
            "companyid" => $companyID,
            "userid" => $userID
        ]);
        $count = intval($statement->fetch()[0]);
        return $count > 0;
    }

    public static function createJoinRequest(string $companyID, string $userID){
        $pdo = Database::connect();
        $query = "INSERT INTO CompanyJoinRequests (CompanyID, UserID, CompanyAccepted) VALUES (:companyid, :userid, 0)";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "companyid" => $companyID,
            "userid" => $userID
        ]);

    }
}

?>