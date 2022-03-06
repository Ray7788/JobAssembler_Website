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
$columns = array("JobID", "Title", "Details", "CompanyID", "UserSeen", "CompanyID", "Name", "Description", "CompanyImage");
//It's SELECT DISTINCT because without it the same row is returned multiple times. I think it's because my INNER JOIN stuff is outdated but this solves it easily.
$query = "SELECT DISTINCT JobPostings.*, UserJobs.UserSeen, Companies.* FROM ((JobPostings
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
        <script>
            var jobCounter = 0;
            //To see whole jobArray do JSON.stringify(jobArray) because it's encoded using json to make it more secure.
            var jobArray = <?php echo json_encode($jobs) ?>;
            var columns = ["JobID", "Title", "Details", "CompanyID", "UserSeen", "CompanyID", "Name", "Description", "CompanyImage"];
            
            function writeToCard(){
                var companyName = jobArray[jobCounter][columns.indexOf("Name")];
                var jobTitle = jobArray[jobCounter][columns.indexOf("Title")];
                var jobDetails = jobArray[jobCounter][columns.indexOf("Details")];
                var companyDescription = jobArray[jobCounter][columns.indexOf("Description")];

                document.getElementById("card").innerHTML = "Company: " + companyName + " <br> "
                 + "Job Title: " + jobTitle + "<br>"
                 + "Job Details: " + "<br> <textarea cols=80 rows=8 readonly>" + jobDetails + "</textarea><br>"
                 + "Company Description: " + "<br> <textarea cols=80 rows=8 readonly>" + companyDescription + "</textarea><br>";
            }

            function buttonPressed(){
                if(jobCounter < jobArray.length-1){
                    jobCounter += 1;
                    writeToCard();
                }else{
                    document.getElementById("card").innerHTML = "Sorry, you've seen every available job.";
                }
                
            }

            function yesPressed(){
                buttonPressed();
            }

            function noPressed(){
                buttonPressed();
            }
        </script>
    </head>
    <body>
        <?php
            echo("You are signed in as: " . $user->username);
        ?>

        <div class="container">
            <div class="box">
                <p id="card" name="card">
                    placeholder
                </p>
                

            </div>  
        </div>
        <div class="buttonHolder">
            <button class="button" onclick="noPressed()">NO</button>
            <button class="button" onclick="yesPressed()">YES</button>
        </div>
    </body>
    <script>
        writeToCard();
    </script>

</html>