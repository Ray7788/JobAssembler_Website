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
//$companyID = $user->company_id;
$companyID = 1;

$applicants = array();
$pdo = Database::connect();

//$columns = array("JobID", "Title", "Details", "CompanyID");
$query = "SELECT * FROM JobPostings WHERE CompanyID = :companyID";
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $companyID]);
$jobs = $statement->fetchAll();

//Make the array of jobs belonging to the company into a string ready for the sql query.


//* FROM UserAccounts WHERE UserJobs.jobID belongs to my companyID AND CompanySeen = 0.
$query = "SELECT DISTINCT UserAccounts.UserID, UserAccounts.Forename, UserAccounts.Surname, UserAccounts.Biography FROM ((UserAccounts 
INNER JOIN UserJobs ON UserJobs.UserID = UserAccounts.UserID) 
INNER JOIN JobPostings ON JobPostings.JobID = UserJobs.JobID) 
WHERE UserJobs.UserAccepted = 1 AND UserJobs.CompanySeen = 0 AND UserJobs.JobID in (2, 3, 4);";
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $companyID]);
$userAccounts = $statements->fetchAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employer Swipe Screen</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        
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
        
        <script>
            var userID = <?php echo($userID); ?>;
            var currentJobID = 0;
            var jobArray = <?php echo json_encode($jobs) ?>;    //If this is empty, disable buttons
            var currentJob = jobArray[currentJobID][1];
            //var userAccounts = <?php echo json_encode($userAccounts) ?>;  //If this is empty, there are no users left to swipe through.
            
            $(function(){
                $(".dropdown-menu a").click(function(){
                    alert($(this).text());
                    currentJob = $(this).text();
                    document.getElementById("jobName").innerHTML = "Current job: " + currentJob;
                });
            });

            function getRightJobs(){
                //When user presses the dropdown button and changes jobs, you want to change the jobs displayed
                //Get array of jobs with jobID just selected.
            }

            function writeToCard(){
                //var forename = 
            }
        </script>
    </head>
    <body>
        <?php
            echo("You are signed in as: " . $user->username);
            //echo(implode(",",$jobs[0]));
            echo($user->companyID);
        ?>
        <p id="jobName" name="jobName">
            placeholder
        </p>
        
        <div class="container">
            <div class="box">
                <p id="card" name="card">
                    
                </p>
                

            </div>  
        </div>
        <div class="buttonHolder">

            <!--<button class="button" id="noButton" onclick="buttonPressed(0)">NO</button>
            <button class="button" id="yesButton" onclick="buttonPressed(1)">YES</button>-->

        </div>
        <div class="buttonHolder">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Choose job
                </button>
                <div class="dropdown-menu">
                    <?php
                        for($x=0; $x<count($jobs);$x++){
                            echo('<a class="dropdown-item">' . $jobs[$x][1]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        document.getElementById("jobName").innerHTML = "Current job: " + currentJob;
        //writeToCard();
    </script>
</html>