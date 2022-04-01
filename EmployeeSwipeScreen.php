<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    header("Location: index.php");
    die(0);
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if (!$user->is_authenticated()) {
    header("Location: index.php");
    die(0);
}
$user->get_user();
if($user->company_id != -1){
    header("Location: main.php");
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
$UserJobsIDs = $statement->fetchAll(PDO::FETCH_ASSOC);
$UserJobsIDs = array_map('implode', $UserJobsIDs);

$query = "SELECT JobID FROM JobPostings";
$statement = $pdo->prepare($query);
$statement->execute();
$JobPostingsIDs = $statement->fetchAll(PDO::FETCH_ASSOC);
$JobPostingsIDs = array_map('implode', $JobPostingsIDs);

//For some reason each number is read twice, so each array element is two numbers. Shouldn't make a difference though.
//The reason the above problem was happening was because the default fetch mode is PDO::FETCH_BOTH which creates an array with both integer and string keys. I have fixed said problem -Max.
for($x=0;$x<count($JobPostingsIDs);$x++){
    if(in_array($JobPostingsIDs[$x], $UserJobsIDs) == false){
        //Need to INSERT this entry
        $query = "INSERT INTO UserJobs(UserID, JobID, UserAccepted, CompanyAccepted, UserSeen, CompanySeen) VALUES (:userID, :jobID, 0, 0, 0, 0)";
        $statement = $pdo->prepare($query);
        $statement->execute([
            "userID" => $userID,
            "jobID" => $JobPostingsIDs[$x]
        ]);
    }
}

//Now do the thing that gets the jobs you haven't seen yet.
//RemoteJob at 6. UserSeen at 7
$columns = array("JobID", "Title", "Details", "CompanyID", "UserSeen", "CompanyID", "Name", "Description", "CompanyImage");
//It's SELECT DISTINCT because without it the same row is returned multiple times. I think it's because my INNER JOIN stuff is outdated but this solves it easily.
$query = "SELECT DISTINCT JobPostings.*, UserJobs.UserSeen, Companies.* FROM ((JobPostings
INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID)
INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID) 
WHERE UserJobs.UserSeen = 0 AND UserJobs.UserID=:userID;";
$statement = $pdo->prepare($query);
$statement->execute(["userID" => $userID]);
$data = $statement->fetchAll(PDO::FETCH_NUM);
$jobs = array_reverse($data);

//This is getting the skills the user has in common with the job. Gets a score for each job that has just been returned
$scores = array();
for($x=0;$x<count($jobs);$x++){
    //Returns the number of skills in common between the user and the job.
    $query = "SELECT COUNT(*) FROM (UserSkills INNER JOIN JobSkills ON UserSkills.SkillID = JobSkills.SkillID) WHERE UserSkills.UserID = :userid AND JobSkills.jobid = :jobid";
    $statement = $pdo->prepare($query);
    $statement->execute([
        "userid" => $userID,
        "jobid" => $jobs[$x][0]
    ]);
    $count = intval($statement->fetch()[0]);
    array_push($scores, $count);
    array_push($jobs[$x], $count);
}
//$jobs[13] has the score
//Latitude and longitude for jobs at 4, 5 respectively.
//Salary at 7
//Get distance for each job

//If the job hunter is remote and the job is remote then add 10 points
//If the job hunter is remote and the job is not remote then add points based on distance
//If the job hunter is not remote then just add points based on distance.

//I've made is so the distance score is still added if the user is remote, but only if the job isn't remote.
if($user->remote){
    for($x=0;$x<count($jobs); $x++){
        $isRemote = $jobs[$x][6];
        if($isRemote == 1){
            $jobs[$x][13] = $jobs[$x][13] + 10;
        }
    }
}

//First get latlong for user signed in
$query = "SELECT Latitude, Longitude FROM UserAccounts WHERE UserID = :userID";
$statement = $pdo->prepare($query);
$statement->execute(["userID" => $userID]);
$data = $statement->fetchAll(PDO::FETCH_NUM);
if((!$data[0][0] == null) && (!$data[0][1] == null)){
    //Both latitude and longitude have to be set
    $latitude1 = $data[0][0];
    $longitude1 = $data[0][1];
    for($x=0;$x<count($jobs);$x++){
        $jobLat = $jobs[$x][4];
        $jobLong = $jobs[$x][5];
        $isRemote = $jobs[$x][6];
        if((!$jobLat == null) && (!$jobLong == null) && ($isRemote == 0)){
            $distance = vincentyGreatCircleDistance($latitude1, $longitude1, $jobLat, $jobLong);
            $distance = round($distance);
            //Add 5 points for 10km, ... , 1 point for less than 50km
            $distanceScore = 0;
            if($distance <= 50000){
                $distanceScore += 1;
            }
            if($distance <= 40000){
                $distanceScore += 1;
            }
            if($distance <= 30000){
                $distanceScore += 1;
            }
            if($distance <= 20000){
                $distanceScore += 1;
            }
            if($distance <= 10000){
                $distanceScore += 1;
            }
            $jobs[$x][13] = $jobs[$x][13] + $distanceScore;
            array_push($jobs[$x], $distance);
        }else{
            array_push($jobs[$x], 0);
        } 
    }
}else{
    for($x=0;$x<count($jobs);$x++){
        array_push($jobs[$x], 0);
    }
}




function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);
  
    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
  
    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
}


// Comparison function
function compare($element1, $element2){
    $job1 = $element1[13];
    $job2 = $element2[13];
    return $job2 - $job1;
}

usort($jobs, 'compare');

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employee Swipe Screen - JobAssembler</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

        <style type="text/css">
            body {
            width: 90%;
			height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url("Images/pedro-lastra-Nyvq2juw4_o-unsplash.jpg");
            background-size: 190%;
            overflow-x: hidden;
            background-repeat: no-repeat;
            }

            .container{
            width: 85vw;
            height: 90vh;
            position: relative;  
            margin: 10%; 
            justify-content: center;
            align-items: center;
            display: flex;
            top: 5vh;
            }
/* ----------------------------------------------------*/
		.cards-wrap {
            border-radius: 15px;
            width: 950px;
			height: 1500px;
			margin: auto;
			perspective: 100px;
			perspective-origin: 50% 90%;
		}
		.card {
            border-radius: 15px;
			width: 800px;
            height: 720px;
			padding: 50px 30px;
			background: #ffffff;
			box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
			position: absolute;
			transform-origin: 50% 50%;
			transition: all 0.8s;
            
		}
		.card.first {
			transform: translate3d(0, 0, 0px);
			z-index: 3;
		}

		.card.second {
			transform: translate3d(0, 0, -10px);
			z-index: 2;
		}

		.card.third {
			transform: translate3d(0, 0, -20px);
			z-index: 1;
		}

		.card.last-card {
			transform: translate3d(0, 0, -20px);
			z-index: 1;
		}

		.card:not(:first-child) {
			opacity: 0.95;
		}

		.card.swipe {
			transform: rotate(30deg) translate3d(120%, -50%, 0px) !important;
			opacity: 0;
			visibility: hidden;
		}

		.card p {
			font-size: 20px;
			font-weight: 400;
			margin-bottom: 20px;
			margin-top: 10px;
		}

/* ----------------------------------------------------------------------------------------------------------------- */
/* For Yes/No Button */
		.option {
			width: 100%;
			padding: 18px 10px 18px 84px;
			text-align: left;
			border: none;
			outline: none;
			cursor: pointer;
			font-size: 16px;
			font-weight: 400;
			background: #f8f8f8 url(ic_option_normal.svg) left 50px center no-repeat;
			background-size: 24px;
		}

		.option:nth-child(2) {
			margin-bottom: 20px;
		}

		.option:hover,
		.option.checked {
			background: #faeeee url(ic_option_checked.svg) left 50px center no-repeat;
		}

        .btn-group{
                justify-content:center;
                display:flex;
                align-items:center;
                height:5em;
                font-size:40px;
                position: sticky;
                bottom: -5vh;
        }
/* ----------------------------------------------------------------------------------------------------------------- */
   /* For navbar */        
        /* For Logo border*/
        .d-inline-block-align-top {border-radius: 5px;}
        /* For DownMenu */
        .navbar-text{
                color: yellow;
            }
       .navbar-nav{
           position:absolute;
           right: 50px;
       } 

        </style>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            var userID = <?php echo($userID); ?>;
            var jobCounter = 0;
            //To see whole jobArray do JSON.stringify(jobArray) because it's encoded using json to make it more secure.
            var jobArray = <?php echo json_encode($jobs) ?>;    //If this is empty, disable buttons
            var columns = ["JobID", "Title", "Details", "CompanyID", "UserSeen", "CompanyID", "Name", "Description", "CompanyImage"];
            var userRemote = <?php echo(intval($user->remote)) ?>;
            
            /*
            for(let i=0; i<jobArray.length; i++){
                console.log(jobArray[i].join());
            }
            */

            function writeToCard(){
                var companyName = jobArray[jobCounter][10];
                var jobTitle = jobArray[jobCounter][columns.indexOf("Title")];
                var jobDetails = jobArray[jobCounter][columns.indexOf("Details")];
                var companyDescription = jobArray[jobCounter][11];
                var distance = jobArray[jobCounter][14];
                distance = distance / 1000; // Convert into km
                var isRemote = jobArray[jobCounter][6];
                var salary = jobArray[jobCounter][7];

                //Only show distance if not remote.
                if(isRemote == 1){
                    document.getElementById("card").innerHTML = "<b>Would you like to apply to this job?</b> <br>"
                    + "This job is remote.<br>"
                    + "Company: " + companyName + " <br> "
                    + "Job Title: " + jobTitle + "<br>"
                    + "Salary: £" + salary + "<br>"
                    + "Job Details: " + "<br> <textarea cols=60 rows=4 readonly>" + jobDetails + "</textarea><br>"
                    + "Company Description: " + "<br> <textarea cols=60 rows=4 readonly>" + companyDescription + "</textarea><br>";
                }else{
                    //If the user is remote but the job isn't, then it says so.
                    if(userRemote == 1){
                        if(distance == 0){
                            document.getElementById("card").innerHTML = "<b>Would you like to apply to this job?</b> <br>"
                            + "Location not found - not remote<br>"
                            + "Company: " + companyName + " <br> "
                            + "Job Title: " + jobTitle + "<br>"
                            + "Salary: £" + salary + "<br>"
                            + "Job Details: " + "<br> <textarea cols=60 rows=4 readonly>" + jobDetails + "</textarea><br>"
                            + "Company Description: " + "<br> <textarea cols=60 rows=4 readonly>" + companyDescription + "</textarea><br>";
                        }else{
                            document.getElementById("card").innerHTML = "<b>Would you like to apply to this job?</b> <br>"
                            + "Distance from you: " + distance + "km - job is not remote<br>"
                            + "Company: " + companyName + " <br> "
                            + "Job Title: " + jobTitle + "<br>"
                            + "Salary: £" + salary + "<br>"
                            + "Job Details: " + "<br> <textarea cols=60 rows=4 readonly>" + jobDetails + "</textarea><br>"
                            + "Company Description: " + "<br> <textarea cols=60 rows=4 readonly>" + companyDescription + "</textarea><br>";
                        }
                        
                    }else{
                        if(distance == 0){
                            document.getElementById("card").innerHTML = "<b>Would you like to apply to this job?</b> <br>"
                            + "Location not found.<br>"
                            + "Company: " + companyName + " <br> "
                            + "Job Title: " + jobTitle + "<br>"
                            + "Salary: £" + salary + "<br>"
                            + "Job Details: " + "<br> <textarea cols=60 rows=4 readonly>" + jobDetails + "</textarea><br>"
                            + "Company Description: " + "<br> <textarea cols=60 rows=4 readonly>" + companyDescription + "</textarea><br>";
                        }else{
                            document.getElementById("card").innerHTML = "<b>Would you like to apply to this job?</b> <br>"
                            + "Distance from you: " + distance + "km<br>"
                            + "Company: " + companyName + " <br> "
                            + "Job Title: " + jobTitle + "<br>"
                            + "Salary: £" + salary + "<br>"
                            + "Job Details: " + "<br> <textarea cols=60 rows=4 readonly>" + jobDetails + "</textarea><br>"
                            + "Company Description: " + "<br> <textarea cols=60 rows=4 readonly>" + companyDescription + "</textarea><br>";
                        }
                        
                    }
                    
                }
                
            }

            function buttonPressed(yesOrNo){
                dataArray = {"userAccepted":yesOrNo, "userID":userID, "jobID":jobArray[jobCounter][columns.indexOf("JobID")]};
                $.ajax({
                    type:"POST",
                    url:"api/jobUpdating.php",
                    data:dataArray,
                    success:function(data){
                        
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
        <!-- Nav bar -->
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark fixed-top">

            <a class="navbar-brand">
            <!-- Brand LOGO -->
            <a class="navbar-brand">
            <img src="Images/Logo1.png" width="30" height="30" class="d-inline-block-align-top" alt="Logo";>
            </a>
            <span class="navbar-text  active" style="color: white;">
            <?php
                echo("You are signed in as:&nbsp;" . $user->username);
            ?>
            </span> 

            <ul class="navbar-nav" >

                <form class="form-inline">
                    <li class="nav-item  active">
                    <a class="nav-link"  href="userSkills.php">My Skills</a>
                    </li>
                    <li class="nav-item  active">
                    <a class="nav-link" href="EmployeeForm.php">Edit Details</a>
                    </li>
                    <li class="nav-item  active">
                    <a class="nav-link" href="JobList.php">Job List</a>
                    </li>
                    <li class="nav-item  active">
                    <a class="btn-danger"  style="margin-left: 30%; padding: 10px; white-space: nowrap;"  href="api/logout.php">Log Out</a>
                    </li>
                </form>
            </ul>
        </nav>
    <div class="container">

        <br>
		<div class="cards-wrap" style="margin-left: 20%;">
			<div class="card first">
                <p id="card" name="card">
                        Sorry, you've seen all the available jobs
                    </p>
                <br>
                <!-- <div class="btn-group"> -->
				<button class="option" id="yesButton" onclick="buttonPressed(1)">YES</button>
				<button class="option" id="noButton" onclick="buttonPressed(0)">NO</button>
                <!-- </div> -->
			</div>

			<div class="card second"></div>
			<div class="card third"></div>
    </div>

    </body>
    <script>
        writeToCard();
        const cardWrap = document.querySelector(".cards-wrap");
		function pickOption() {
			const topCard = document.querySelector(".card:first-child");
			const tempStr = topCard.innerHTML;
			topCard.classList.add("swipe");
			setTimeout(function() {
				topCard.remove();
				const cards = document.querySelectorAll(".card");
				cards[0].style.transform = "translate3d(0, 0, 0px)";
				cards[0].style.zIndex = "3";
				cards[0].innerHTML = tempStr;
				cards[1].style.transform = "translate3d(0, 0, -10px)";
				cards[1].style.zIndex = "2";
				cardWrap.insertAdjacentHTML(
					"beforeend",
					'<div class="card last-card"></div>'
				);
				const newOptions = document.querySelectorAll(".option");
				newOptions.forEach(optionBtn =>
					optionBtn.addEventListener("click", pickOption)
				);
			}, 300);
		}

		const optionBtns = document.querySelectorAll(".option");
		optionBtns.forEach(optionBtn =>
			optionBtn.addEventListener("click", pickOption)
		);

    </script>
</html>