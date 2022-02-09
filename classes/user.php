<?php
require_once("prevent_direct_access.php");

class User
{
    private PDO $pdo;
    private bool $authenticated = false;

    public int $user_id;
    public string $username;
    public string $forename;
    public string $surname;
    public string $biography = "";

    #Pepper used in password hashing to give some protection against password shucking. https://www.youtube.com/watch?v=OQD3qDYMyYQ
    private static string $pepper = "v6uAX32o";

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
        $to_hash = $pre_hash . User::$pepper;
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

    public static function check_password(PDO $pdo, int $id, string $password): bool {
        $query = "SELECT `PasswordHash` FROM `UserAccounts` WHERE UserID = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$id]);
        $result = $statement->fetch();
        if ($result == false) return false;
        $hash = strval($result["PasswordHash"]);
        $pre_hash = hash("sha256", $password);
        $to_check = $pre_hash . User::$pepper;
        return password_verify($to_check, $hash);
    }

    function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate($id, $password): bool {
        $this->user_id = $id;
        $verified = User::check_password($this->pdo, $id, $password);
        if (!$verified) return false;$query = "SELECT * FROM `UserAccounts` WHERE UserID = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
        $result = $statement->fetch();
        $this->username = $result["Username"];
        $this->forename = $result["Forename"];
        $this->surname = $result["Surname"];
        $this->biography = $result["Biography"];
        return true;
    }

    public function is_authenticated(): bool {
        return $this->authenticated;
    }
}