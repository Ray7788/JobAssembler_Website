<?php

class User
{
    private PDO $pdo;

    public int $user_id;
    public string $username;
    public string $forename;
    public string $surname;
    public string $biography = "";

    public static function check_username_exists(PDO $pdo, string $username): bool {
        if (strlen($username) == 0) return true;
        $query = "SELECT COUNT(*) FROM `UserAccounts` WHERE Username = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$username]);
        $count = intval($statement->fetch()[0]);
        return $count > 0;
    }

    public static function get_user_id(PDO $pdo, string $username): int {
        $query = "SELECT `UserID` FROM `UserAccounts` WHERE Username = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$username]);
        $result = $statement->fetch();
        if ($result == false) {
            return -1;
        }
        else {
            return intval($result[0]);
        }
    }

    /**
     * @throws Exception
     */
    public static function create_user(PDO $pdo, string $username, string $password, string $forename, string $surname): bool {
        $pre_hash = hash("sha256", $password);
        #Pepper used to give some protection against password shucking
        $pepper = "v6uAX32o";
        $to_hash = $pre_hash . $pepper;
        if (strlen($to_hash) > 72) {
            throw new Exception("Fatal error hashing password.");
        }
        $hash = password_hash($to_hash, PASSWORD_BCRYPT);
        $query = "INSERT INTO `UserAccounts` (`Username`, `Forename`, `Surname`, `Biography`, `PasswordHash`) VALUES (:username, :forename, :surname, '', :hash)";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "username" => $username,
            "forename" => $forename,
            "surname" => $surname,
            "hash" => $hash
        ]);
    }

    function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}