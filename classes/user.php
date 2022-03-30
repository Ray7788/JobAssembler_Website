<?php
require_once(__DIR__ . "/prevent_direct_access.php");

class User
{
    private bool $authenticated = false;

    public int $user_id;
    public string $username;
    public string $forename;
    public string $surname;
    public string $biography = "";
    public string $image_url;
    public int $company_id;
    public float $latitude;
    public float $longitude;
    public string $email;

    #Pepper used in password hashing to give some protection against password shucking. https://www.youtube.com/watch?v=OQD3qDYMyYQ
    private static string $pepper = "v6uAX32o";

    public static function check_username_exists(string $username): bool {
        $pdo = Database::connect();
        if (strlen($username) == 0) return true;
        $query = "SELECT COUNT(*) FROM `UserAccounts` WHERE Username = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$username]);
        $count = intval($statement->fetch()[0]);
        return $count > 0;
    }

    public static function get_user_id(string $username): int {
        $pdo = Database::connect();
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

    public static function create_user(string $username, string $password, string $forename, string $surname): bool {
        $pdo = Database::connect();
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

    public static function check_password(int $id, string $password): bool {
        $pdo = Database::connect();
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

    public function authenticate($id, $password): bool {
        $this->user_id = $id;
        $verified = User::check_password($id, $password);
        if (!$verified) return false;
        $this->get_user();
        $this->authenticated = true;
        return true;
    }

    public function get_user(int $id = null): array {
        $pdo = Database::connect();
        if (isset($id)){
            $this->user_id = $id;
        }
        $query = "SELECT Username, Forename, Surname, Biography, ProfileImage, CompanyID, Latitude, Longitude, Email FROM `UserAccounts` WHERE UserID = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$this->user_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->username = $result["Username"];
        $this->forename = $result["Forename"];
        $this->surname = $result["Surname"];
        $this->biography = $result["Biography"];
        $this->image_url = is_null($result["ProfileImage"]) ? "" : $result["ProfileImage"];
        $this->company_id = is_null($result["CompanyID"]) ? -1 : intval($result["CompanyID"]);
        $this->latitude = floatval($result["Latitude"]);
        $this->longitude = floatval($result["Longitude"]);
        $this->email = is_null($result["Email"]) ? "" : $result["Email"];
        unset($result["Latitude"]);
        unset($result["Longitude"]);
        return $result;
    }

    public function is_authenticated(): bool {
        return $this->authenticated;
    }

    public function revoke_auth(): void {
        $this->authenticated = false;
    }

    public function set_company_id(string $userID, string $companyID): void {
        $pdo = Database::connect();
        $query = "UPDATE `UserAccounts` SET CompanyID = :companyID WHERE UserID = :userID";
        $statement = $pdo->prepare($query);
        $statement->execute([
            "userID" => $userID,
            "companyID" => $companyID
        ]);
    }

    public function update_property(string $property, mixed $value): void {
        if (!isset($this->user_id)) {
            return;
        }
        $pdo = Database::connect();
        $query = "UPDATE `UserAccounts` SET $property = :val WHERE UserID = :userID";
        $statement = $pdo->prepare($query);
        $statement->execute([
            "userID" => $this->user_id,
            "val" => $value
        ]);
        $this->get_user();
    }

    public function set_location(float $latitude, float $longitude): void {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $pdo = Database::connect();
        if ($latitude == 0 && $longitude == 0) {
            $query = "UPDATE `UserAccounts` SET Latitude = NULL, Longitude = NULL WHERE UserID = :userID";
            $statement = $pdo->prepare($query);
            $statement->execute([
                "userID" => $this->user_id
            ]);
        }
        else {
            $query = "UPDATE `UserAccounts` SET Latitude = :lat, Longitude = :lng WHERE UserID = :userID";
            $statement = $pdo->prepare($query);
            $statement->execute([
                "userID" => $this->user_id,
                "lat" => $latitude,
                "lng" => $longitude
            ]);
        }

    }
}