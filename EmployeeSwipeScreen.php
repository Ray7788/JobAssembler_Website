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
$jobs = array();
$pdo = Database::connect();
//NEED to insert rows with UserSeen=0 for jobs that aren't in the database yet.
/*
INSERT INTO UserJobs (UserID, JobID, UserAccepted, CompanyAccepted, UserSeen) VALUES (17, 2, 0, 0, 0)
*/
/*
Look through the database and see if there is an entry in UserJobs for every job in JobPostings
1. Get the list of jobIDs related to this userID (UserJobs)
2. Get list of jobIDs in JobPostings
3. Go through JobIDs in JobPostings. If there isn't the same number in UserJobs JobIDs:
3a. INSERT INTO UserJobs (UserID, JobID, UserAccepted, CompanyAccepted, UserSeen) VALUES (:userID, :jobID, 0, 0, 0) 

*/
$query = "SELECT JobID FROM UserJobs WHERE UserID = :userID";
$statement = $pdo->prepare($query);
$statement->execute(["userID" => $userID]);
$UserJobsIDs = $statement->fetchAll();
$UserJobsIDs = array_map('implode', $UserJobsIDs);
//echo(implode($UserJobsIDs));

$query = "SELECT JobID FROM JobPostings";
$statement = $pdo->prepare($query);
$statement->execute();
$JobPostingsIDs = $statement->fetchAll();
$JobPostingsIDs = array_map('implode', $JobPostingsIDs);
//echo("HEELOOO". implode('\n', array_map('implode', $JobPostingsIDs)));

//For some reason each number is read twice, so each array element is two numbers. Shouldn't make a difference though.

for($x=0;$x<count($JobPostingsIDs);$x++){
    if(in_array($JobPostingsIDs[$x], $UserJobsIDs) == false){
        //Need to INSERT this entry
        $query = "INSERT INTO UserJobs(UserID, JobID, UserAccepted, CompanyAccepted, UserSeen) VALUES (:userID, :jobID, 0, 0, 0, 0)";
        $statement = $pdo->prepare($query);
        $statement->execute([
            "userID" => $userID,
            "jobID" => $JobPostingsIDs[$x][0]
        ]);
        
    }
}

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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            var userID = <?php echo($userID); ?>;
            var jobCounter = 0;
            //To see whole jobArray do JSON.stringify(jobArray) because it's encoded using json to make it more secure.
            var jobArray = <?php echo json_encode($jobs) ?>;    //If this is empty, disable buttons
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

            function buttonPressed(yesOrNo){
                dataArray = {"userAccepted":yesOrNo, "userID":userID, "jobID":jobArray[jobCounter][columns.indexOf("JobID")]};
                $.ajax({
                    type:"POST",
                    url:"api/jobUpdating.php",
                    data:dataArray,
                    success:function(data){
                        alert("Job Done")
                    },
                    error: function(xhr){
                        var obj = xhr.responseJSON;
                        if(Object.keys(obj).includes("message")){
                            alert(obj["message"]);
                        }else{
                            alert("An unknown error has occurred. Please try again later.")
                        }
                    }
                })
                if(jobCounter < jobArray.length-1){
                    jobCounter += 1;
                    writeToCard();
                }else{
                    document.getElementById("card").innerHTML = "Sorry, you've seen every available job.";
                    document.getElementById("noButton").disabled = true;
                    document.getElementById("yesButton").disabled = true;
                }
                
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
                    Sorry, you've seen all the available jobs
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