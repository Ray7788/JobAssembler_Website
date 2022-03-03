<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /index.php");
    die(0);
}
$user = $_SESSION["user"];
if (!$user->is_authenticated()) {
    header("Location: /index.php");
    die(0);
}
$jobs = array();
$pdo = Database::connect();
/*
Query:
SELECT JobPostings.*, UserJobs.UserSeen, Companies.* FROM ((JobPostings
INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID)
INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID) 
WHERE UserJobs.UserSeen = 0;
*/
$columns = array("JobID", "Title", "Details", "CompanyID", "UserSeen", "CompanyID", "Name", "Description", "CompanyImage");
$query = "SELECT JobPostings.*, UserJobs.UserSeen, Companies.* FROM ((JobPostings
INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID)
INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID) 
WHERE UserJobs.UserSeen = 0;";
$statement = $pdo->prepare($query);
$statement->execute();
$data = $statement->fetchAll();
$jobs = array_reverse($data);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employee Swipe Screen</title>
        <style type="text/css">
            .container{
                justify-content:center;
                display: flex;
                align-items:center;
                height: 12em;
                font-size:2.5em;
                position:relative;
            }
            .box{
                background: bisque;
                border-radius: 5px;
                box-shadow: 3px 10px 15px #abc;
                
            }
            .buttonHolder{
                justify-content:center;
                display:flex;
                align-items:center;
                height:5em;
                font-size:40px;
                position:relative;
            }
            .button {
                margin-left:auto;
                margin-right:auto;
                border: none;
                color: black;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                background-color:aqua;
            }
        </style>
    </head>
    <body>
        <?php
            //$user = $_SESSION["user"];
            echo("You are signed in as: " . $user->username);
        ?>

        <div class="container">
            <div class="box">
                <?php
                    echo("Company: " . $jobs[0][array_search("Name", $columns)] . "<br>");
                    echo("Job Title: " . $jobs[0][array_search("Title", $columns)] . "<br>");
                    echo("Job Details: " . "<br>" . 
                    "<textarea cols=80 rows=8 readonly>".$jobs[0][array_search("Details", $columns)]."</textarea><br>");
                    echo("Company Description:" . "<br>" . 
                    "<textarea cols=80 rows=8 readonly>".$jobs[0][array_search("Description", $columns)]."</textarea><br>");
                ?>

            </div>  
        </div>
        <div class="buttonHolder">
            <button class="button">NO</button>
            <button class="button">YES</button>
        </div>
    </body>
</html>