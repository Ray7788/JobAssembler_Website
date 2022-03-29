<?php
require_once("prevent_direct_access.php");
require_once("database.php");

class skills{
    public static function deleteSkillsForUser(string $userID){
        $pdo = Database::connect();
        $query = "DELETE FROM UserSkills WHERE UserID = :userID";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "userID" => $userID
        ]);
    }

    public static function getSkillIDs(array $softSkills){
        
    }

    public static function insertSoftSkills(array $softSkills, string $userID){
        $pdo = Database::connect();
        $acceptArray = array();
        $skillsStr = "";
        for($x=0; $x<count($softSkills); $x++){
            $skillsStr .= "'";
            $skillsStr .= $softSkills[$x];
            $skillsStr .= "' ,";
        }
        $skillsStr = substr($skillsStr, 0, -1); // Removes last comma

        $query = "SELECT SkillID FROM Skills WHERE Name IN (" . $skillsStr . ")";
        $statement = $pdo->prepare($query);
        array_push($acceptArray, $statement->execute());
        $skillIDs = $statement->fetchAll(PDO::FETCH_NUM);
        
        for($x=0; $x<count($skillIDs); $x++){
            $query = "INSERT INTO UserSkills (UserID, SkillID) VALUES (".$userID.", ".$skillIDs[$x][0].");";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }
        if(in_array(false, $acceptArray)){
            return false;
        }else{
            return true;
        }
    }

    public static function insertSkillsForUser(array $languageYears, string $userID){
        $pdo = Database::connect();
        //These are the SkillIDs for the 'Basic' version of the skills in the Skills table. 
        $basicSkills = [1, 4, 7, 10, 13, 16, 19, 22];
        $execute1 = true;
        $execute2 = true;
        $execute3 = true;
        for($x=0; $x<count($basicSkills); $x++){
            //$x is the index of the language in the languageYears array
            //If it's <=2 years, it's 'basic'. If it's between 3 and 7 it's intermediate. If it's 8, 9 or 10 then it's advanced.
            if(!$languageYears[$x] == 0){
                if($languageYears[$x] >= 8){
                    $query1 = "INSERT INTO UserSkills VALUES (:userid, :skillid)";
                    $statement1 = $pdo->prepare($query1);
                    $skillID = $basicSkills[$x] + 2;
                    $execute1 = $statement1->execute([
                        "userid" => $userID,
                        "skillid" => $skillID
                    ]);
                }
                if($languageYears[$x] >= 3){
                    $query2 = "INSERT INTO UserSkills VALUES (:userid, :skillid)";
                    $statement2 = $pdo->prepare($query2);
                    $skillID = $basicSkills[$x] + 1;
                    $execute2 = $statement2->execute([
                        "userid" => $userID,
                        "skillid" => $skillID
                    ]);
                }
                $query3 = "INSERT INTO UserSkills VALUES (:userid, :skillid)";
                $statement3 = $pdo->prepare($query3);
                $skillID = $basicSkills[$x];
                $execute3 = $statement3->execute([
                    "userid" => $userID,
                    "skillid" => $skillID
                ]);

            }
        }
        if(($execute1 == false) || ($execute2 == false) || ($execute3 == false)){
            return false;
        }else{
            return true;
        }
    }
}