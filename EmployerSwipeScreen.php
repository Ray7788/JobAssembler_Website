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
$companyID = 1;

$applicants = array();
$pdo = Database::connect();

$query = "SELECT * FROM JobPostings WHERE CompanyID = :companyID";
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $companyID]);
$jobs = $statement->fetchAll();

$jobString = "";
//Make the array of job IDs belonging to the company into a string ready for the sql query.
//Want it like 2,3,4
for($x=0; $x<count($jobs); $x++){
    $jobString .= $jobs[$x][0];
    $jobString .= ",";
}
$jobString = substr($jobString, 0, -1); //Removes the last comma


$query = "SELECT DISTINCT UserAccounts.UserID, UserAccounts.Forename, UserAccounts.Surname, UserAccounts.Biography, UserJobs.JobID FROM ((UserAccounts 
INNER JOIN UserJobs ON UserJobs.UserID = UserAccounts.UserID) 
INNER JOIN JobPostings ON JobPostings.JobID = UserJobs.JobID) 
WHERE UserJobs.UserAccepted = 1 AND UserJobs.CompanySeen = 0 AND UserJobs.JobID in (" . $jobString . ");";
//Printing two of each person because
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $companyID]);
$userAccounts = $statement->fetchAll(PDO::FETCH_NUM);
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
                padding: 30px 60px;
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
            var jobArray = <?php echo json_encode($jobs) ?>;    //If this is empty, disable buttons
            var currentJobID = jobArray[0][0];
            var currentJob = jobArray[currentJobID][1];
            var userAccounts = <?php echo json_encode($userAccounts) ?>;  //If this is empty, there are no users left to swipe through.
            var userCounter = 0;
            var userAccountsForJob = [];

            $(function(){
                $(".dropdown-menu a").click(function(){
                    currentJob = $(this).text();
                    currentJobID = $(this).attr('id');      //Gets the ID of the dropdown item
                    currentJobID = currentJobID.substring(8); //Every ID has 'dropdown' at the start. Remove it to just get the number
                    //Right now 'currentJobID' is only relative to the list in the dropdown. Need to make it relative to the array from the database
                    currentJobID = jobArray[currentJobID][0];
                    getRightJobs();
                    document.getElementById("jobName").innerHTML = "Current job: " + currentJob;
                });
            });

            function getRightJobs(){
                //NOT GETTING THE RIGHT JOBS AT THE MOMENT. maybe currentjobid is always 2.
                userAccountsForJob = [];
                userCounter = 0;
                //When user presses the dropdown button and changes jobs, you want to change the jobs displayed
                //Get array of jobs with jobID just selected.
                for(let i=0; i<userAccounts.length;i++){
                    console.log(userAccounts[i].join());
                    if(userAccounts[i][4] == currentJobID){
                        console.log(currentJobID);
                        //console.log(userAccounts[i].join());
                        userAccountsForJob.push(userAccounts[i]);
                    }
                }
                if(userAccountsForJob.length == 0){
                    document.getElementById("card").innerHTML = "Sorry, you've seen all available job applicants.";
                    document.getElementById("noButton").disabled = true;
                    document.getElementById("yesButton").disabled = true;
                }else{
                    document.getElementById("noButton").disabled = false;
                    document.getElementById("yesButton").disabled = false;
                    writeToCard();
                }
            }

            function writeToCard(){
                var forename = userAccountsForJob[userCounter][1];
                var surname = userAccountsForJob[userCounter][2];
                var biography = userAccountsForJob[userCounter][3];
                
                document.getElementById("card").innerHTML = ("Forename: " + forename + " <br> "
                + "Surname: " + surname + " <br> "
                + "Biography: " + "<br> <textarea columns=120 rows=4 readonly>" + biography + "</textarea><br>");
            }

            function buttonPressed(yesOrNo){
                //dataArray = {"companyAccepted":yesOrNo, "userID":userAccounts[currentUser][0], "jobID":currentJobID};
                dataArray = {"companyAccepted":yesOrNo, "userID":userAccountsForJob[userCounter][0], "jobID":currentJobID};
                $.ajax({
                    type:"POST",
                    url:"api/companyJobUpdating.php",
                    data:dataArray,
                    success:function(data){
                        alert("User Done");
                    },
                    error: function (xhr){
                        var obj = xhr.responseJSON;
                        if(Object.keys(obj).includes("message")){
                            alert(obj["message"]);
                        }else{
                            alert("An unknown error has occurred. Please try again later.");
                        }
                    }
                })
                if(userCounter < userAccountsForJob.length-1){
                    userCounter += 1;
                    writeToCard();
                }else{
                    document.getElementById("card").innerHTML = "Sorry, you've seen every current available job applicant. Try changing to another job.";
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
        <p id="jobName" name="jobName">
            placeholder
        </p>
        
        <div class="container">
            <div class="box">
                <p id="card" name="card">
                    Sorry, you've seen all available jobs.
                </p>
            </div>  
        </div>
        <div class="buttonHolder">

            <button class="button" id="noButton" onclick="buttonPressed(0)">NO</button>
            <button class="button" id="yesButton" onclick="buttonPressed(1)">YES</button>

        </div>
        <div class="buttonHolder">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Choose job
                </button>
                <div class="dropdown-menu">
                    <?php
                        for($x=0; $x<count($jobs);$x++){
                            echo('<a class="dropdown-item" id="dropdown'.$x.'">' . $jobs[$x][1]);
                            //set ID to dropdown$x  8
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        document.getElementById("jobName").innerHTML = "Current job: " + currentJob;
        getRightJobs();
        writeToCard();
    </script>
</html>