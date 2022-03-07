<?php
require_once(__DIR__ . "/prevent_direct_access.php");
require_once(__DIR__ . "/database.php");

class UserJob{
    public static function update_employee($userAccepted, $userID, $jobID){
        $pdo = Database::connect();
        $query = "UPDATE UserJobs SET UserAccepted = :userAccepted, UserSeen = 1 WHERE UserID=:userID AND JobID=:jobID";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "userAccepted" => $userAccepted,
            "userID" => $userID,
            "jobID" => $jobID
        ]);
    }

    //I haven't actually used this yet, but it might come in handy in the future?
    public static function insert_employee($userAccepted, $userID, $jobID){
        $pdo = Database::connect();
        $query = "INSERT INTO UserJobs (UserID, JobID, UserAccepted, UserSeen) VALUES (:userID, :jobID, :userAccepted, :userSeen)";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "userID" => $userID,
            "jobID" => $jobID,
            "userAccepted" => $userAccepted,
            "userSeen" => 1
        ]);
    }
    
}

?>