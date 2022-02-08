<?php

class User
{
    private PDO $pdo;

    public int $user_id;
    public string $username;
    public string $forename;
    public string $surname;
    public string $biography;

    public static function check_username(PDO $pdo, string $username): bool {
        if (strlen($username) == 0) return false;
        $query = "SELECT COUNT(*) FROM `UserAccounts` WHERE Username = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$username]);
        $count = intval($statement->fetch()[0]);
        return $count > 0;
    }

    public static function get_user_id(PDO $pdo, string $username): int {
        $query = "SELECT UserID FROM `UserAccounts` WHERE Username = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$username]);
        $result = $statement->fetch()[0];
        if (is_null($result)) {
            return -1;
        }
        else {
            return intval($result);
        }
    }

    function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}