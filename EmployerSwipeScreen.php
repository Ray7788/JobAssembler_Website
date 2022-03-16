<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /index.php");
    die(0);
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if (!$user->is_authenticated()) {
    header("Location: /index.php");
    die(0);
}
$applicants = array();
$pdo = Database::connect();

$query = "SELECT * FROM JobPostings WHERE CompanyID = :companyID";
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $user->company_id]);
$data = $statement->fetchAll();



//Now do the thing that gets the jobs you haven't seen yet.
$columns = array("JobID", "Title", "Details", "CompanyID", "UserSeen", "CompanyID", "Name", "Description", "CompanyImage");
//It's SELECT DISTINCT because without it the same row is returned multiple times. I think it's because my INNER JOIN stuff is outdated but this solves it easily.
$query = "SELECT DISTINCT JobPostings.*, UserJobs.UserSeen, Companies.* FROM ((JobPostings
INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID)
INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID) 
WHERE UserJobs.UserSeen = 0 AND UserJobs.UserID=:userID;";
$statement = $pdo->prepare($query);
$statement->execute(["userID" => $userID]);
$data = $statement->fetchAll();
$jobs = array_reverse($data);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employer Swipe Screen</title>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            var userID = <?php echo($userID); ?>;
            
            
        </script>
    </head>
    <body>
        <?php
            echo("You are signed in as: " . $user->username);
        ?>

        <div class="container">
            <div class="box">
                <p id="card" name="card">
                    
                </p>
                

            </div>  
        </div>
        <div class="buttonHolder">

            <button class="button" id="noButton" onclick="buttonPressed(0)">NO</button>
            <button class="button" id="yesButton" onclick="buttonPressed(1)">YES</button>
        </div>
    </body>
    <script>
        writeToCard();
    </script>
</html>