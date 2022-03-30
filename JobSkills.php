<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();

if(!isset($_SESSION["user"])){
	header("Location: /index.php");
	die(0);
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if(!$user->is_authenticated()){
	header("Location: /index.php");
	die(0);
}
if($user->company_id == -1){
    header("Location: /main.php");
    die(0);
}
$companyID = $user->company_id;

$pdo = Database::connect();

$query = "SELECT * FROM JobPostings WHERE CompanyID = :companyID";
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $companyID]);
$jobs = $statement->fetchAll();

//THIS NEEDS CHANGING TO THE JOB THE EMPLOYER IS LOOKING AT IN EMPLOYERSWIPESCREEN!!!
//$currentJobID = 19;
//$currentJob = "Test Job 19";

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Job Skills Form</title>
	<style>
    body {
        display: flex;
        justify-content: center;
        background-image: linear-gradient(to left, #e1eec3, #f05053);

    }
    .container-fluid{
        background-color: #fff;
        width: 650px;
        height: 1700px;
        position: relative;
        display: flex;
        border-radius: 20px;
        justify-content: center;
        align-items: center;
        top: 50px;
    }
    .b{
        width: 500px;
        overflow: hidden;
        margin-bottom: 1em;
    }
    .display-1{
        font: 900 24px '';
        font-size:2.5em;
        font-family:"Helvetica";
        margin: 5px 0;
        text-align: center;
        letter-spacing: 1px;
    }
    .e{
        width: 100%;
        margin-bottom: 20px;
        outline: none;
        border: 0;
        padding: 5px;
        border-bottom: 2px solid rgb(60,60,70);
        
    }
</style>	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script>
		var userID = <?php echo($userID); ?>;
		var jobArray = <?php echo json_encode($jobs) ?>; 
        var currentJobID = jobArray[0][0];
        var currentJob = jobArray[0][1];   

		$(function(){
                $(".dropdown-menu a").click(function(){
                    currentJob = $(this).text();
                    currentJobID = $(this).attr('id');      //Gets the ID of the dropdown item
                    currentJobID = currentJobID.substring(8); //Every ID has 'dropdown' at the start. Remove it to just get the number
                    //Right now 'currentJobID' is only relative to the list in the dropdown. Need to make it relative to the array from the database
                    currentJobID = jobArray[currentJobID][0];
                    
                });
            });

		$(document).ready(function(){
			$("#userSkillsForm").submit(function(e){
				e.preventDefault();
				checkedArray = [];
				for(let i=0 ; i<10; i++){
					checkedArray.push(document.getElementById("soft" + i).checked);
				}
				//emoIntel, patience, adapt, projManage, probSolv, teamwork, interpersonal, leadership, time, decisiveness
				dataArray = {"userID":userID, "javaYears":slider1.value, "pythonYears":slider2.value, "csharpYears":slider3.value, "htmlYears":slider4.value, 
				"phpYears":slider5.value, "cssYears":slider6.value, "cplusYears":slider7.value, "sqlYears":slider8.value,
				"emoIntel":checkedArray[0], "patience":checkedArray[1], "adapt":checkedArray[2], "projManage":checkedArray[3],
				"probSolv":checkedArray[4], "teamwork":checkedArray[5], "interpersonal":checkedArray[6],
				"leadership":checkedArray[7], "time":checkedArray[8], "decisiveness":checkedArray[9], "isCompany":'Yes', "jobID":"19"};
				$.ajax({
					type:"POST",
					url:"api/skillAdding.php",
					data:dataArray,
					success:function(data){
						window.location = "EmployerSwipeScreen.php";
					},
					error: function(xhr){
						var obj = xhr.responseJSON;
						if(Object.keys(obj).includes("message")){
							alert(obj["message"]);
						}else{
							alert("An unknown error has occurred. Please try again later.");
						}
					}
				})
			})
		})

	</script>
</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <!-- Brand LOGO -->
        <a class="navbar-brand">
            <img src="Images/Logo1.png" width="30" height="30" class="d-inline-block-align-top" alt="Logo";>
        </a>
        <span class="navbar-text" style="white-space: nowrap">
            <?php
                echo("You are signed in as: &nbsp;" . $user->username . "&nbsp &nbsp");
            ?>
        </span>   
		<ul class="navbar-nav" style="margin-left: 10%;">
		<!-- Dropdown -->
		<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Choose Jobs</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">
                        <?php
                                        for($x=0; $x<count($jobs);$x++){
                                            echo('<a class="dropdown-item" id="dropdown'.$x.'">' . $jobs[$x][1]);
                                            //set ID to dropdown$x  8
                                        }
                                    ?>
                        </a>
                    </div>
        </li>
    
		<!-- Links -->
			<a class="nav-link" href="EmployerSwipeScreen.php" style="margin-left:5%; white-space: nowrap;">Home</a>
			<a class="nav-link" href="ApplicantList.php" style="margin-left:5%; white-space: nowrap;">Applicant List</a>
			<a class="nav-link" href="CompanyAddUsers.php" style="margin-left:5%; white-space: nowrap;">Add Users</a>
			<a class="btn-danger" style="margin-left: 0%; padding: 10px; white-space: nowrap;"  href="index.php" >Log Out</a>            
        </ul>
    </nav>


	<div class="container-fluid" style="margin-bottom: 100px">
    <div class="b">

<form id="userSkillsForm" name="UserSkills">
	<h1 class="display-1">Job Skills Form</h1>
    <br>
    <p id="jobName"></p>
    <br>
	<h3 class="d" >Please be as precise as possible to make sure you see the most suitable candidates. <br></h3>
	<hr>
    <h2>Programming languages experience: </h2>
	<h3>Please select approximately how many years of experience you would like your candidate to have in each language.</h3>
	<label for="Javaexp">Java Experience: </label>
	<input type="range" id="Javaexp" name="Javaexp" min="0" max="10" step="1" value="0">
	<p><span id="javaOut"></span> Years</p>
	<script>
		var slider1 = document.getElementById("Javaexp");
		var output1 = document.getElementById("javaOut");
		output1.innerHTML = slider1.value;

		slider1.oninput = function(){
			output1.innerHTML = this.value;
		}
	</script>

	<label for="Pythonexp">Python Experience: </label>
	<input type="range" id="Pythonexp" name="Pythonexp" min="0" max="10" step="1" value="0">
	<p><span id="pythonOut"></span> Years</p>
	<script>
		var slider2 = document.getElementById("Pythonexp");
		var output2 = document.getElementById("pythonOut");
		output2.innerHTML = slider2.value;

		slider2.oninput = function(){
			output2.innerHTML = this.value;
		}
	</script>
	<label for="Cexp">C# Experience: </label>
	<input type="range" id="Cexp" name="Cexp" min="0" max="10" step="1" value="0">
	<p><span id="c#Out"></span> Years</p>
	<script>
		var slider3 = document.getElementById("Cexp");
		var output3 = document.getElementById("c#Out");
		output3.innerHTML = slider3.value;

		slider3.oninput = function(){
			output3.innerHTML = this.value;
		}
	</script>
	<label for="HTMLexp">HTML Experience: </label>
	<input type="range" id="HTMLexp" name="HTMLexp" min="0" max="10" step="1" value="0">
	<p><span id="htmlOut"></span> Years</p>
	<script>
		var slider4 = document.getElementById("HTMLexp");
		var output4 = document.getElementById("htmlOut");
		output4.innerHTML = slider4.value;

		slider4.oninput = function(){
			output4.innerHTML = this.value;
		}
	</script>
	<label for="PHPexp">PHP Experience: </label>
	<input type="range" id="PHPexp" name="PHPexp" min="0" max="10" step="1" value="0">
	<p><span id="phpOut"></span> Years</p>
	<script>
		var slider5 = document.getElementById("PHPexp");
		var output5 = document.getElementById("phpOut");
		output5.innerHTML = slider5.value;

		slider5.oninput = function(){
			output5.innerHTML = this.value;
		}
	</script>
	<label for="CSSexp">CSS Experience: </label>
	<input type="range" id="CSSexp" name="CSSexp" min="0" max="10" step="1" value="0">
	<p><span id="cssOut"></span> Years</p>
	<script>
		var slider6 = document.getElementById("CSSexp");
		var output6 = document.getElementById("cssOut");
		output6.innerHTML = slider6.value;

		slider6.oninput = function(){
			output6.innerHTML = this.value;
		}
	</script>
	<label for="C++Exp">C++ Experience: </label>
	<input type="range" id="C++exp" name="C++exp" min="0" max="10" step="1" value="0">
	<p><span id="c++Out"></span> Years</p>
	<script>
		var slider7 = document.getElementById("C++exp");
		var output7 = document.getElementById("c++Out");
		output7.innerHTML = slider7.value;

		slider7.oninput = function(){
			output7.innerHTML = this.value;
		}
	</script>
	<label for="SQLExp">SQL Experience: </label>
	<input type="range" id="SQLExp" name="SQLExp" min="0" max="10" step="1" value="0">
	<p><span id="sqlOut"></span> Years</p>
	<script>
		var slider8 = document.getElementById("SQLExp");
		var output8 = document.getElementById("sqlOut");
		output8.innerHTML = slider8.value;

		slider8.oninput = function(){
			output8.innerHTML = this.value;
		}
	</script>

	<h2>Soft Skills Checklist: </h2>
	<h3>Tick the box if a candidate having this skill is a priority.</h3>
	<label for="emotionalIntelligence">Emotional Intelligence: </label>
	<input type="checkbox" name="emotionalIntelligence" id="soft0" value="emotionalIntelligence" style="text-align: center">
	<br>
	<label for="patience">Patience: </label>
	<input type="checkbox" name="patience" id="soft1" value="patience">
	<br>
	<label for="adaptability">Adaptability: </label>
	<input type="checkbox" name="adaptability" id="soft2" value="adaptability">
	<br>
	<label for="projectManagement">Project Management: </label>
	<input type="checkbox" name="projectManagement" id="soft3" value="projectManagement">
	<br>
	<label for="probSolving">Problem Solving: </label>
	<input type="checkbox" name="probSolving" id="soft4" value="probSolving">
	<br>
	<label for="teamworkCollab">Teamworking and Collaboration: </label>
	<input type="checkbox" name="teamworkCollab" id="soft5" value="teamworkCollab">
	<br>
	<label for="interPersonal">Interpersonal Skills: </label>
	<input type="checkbox" name="interPersonal" id="soft6" value="interPersonal">
	<br>
	<label for="leadership">Leadership Skills: </label>
	<input type="checkbox" name="leadership" id="soft7" value="leadership">
	<br>
	<label for="timeManagement">Time Management: </label>
	<input type="checkbox" name="timeManagement" id="soft8" value="timeManagement">
	<br>
	<label for="decisiveness">Decisiveness: </label>
	<input type="checkbox" name="decisiveness" id="soft9" value="decisiveness">
	<br><hr>
     <button type="button" onclick="window.location.href='EmployerSwipeScreen.php';" class="button">Cancel</button>
     <input type="submit" value="Submit" class="button">
	</form>
    </div>
    </div>
</body>
<script>
    document.getElementById("jobName").innerHTML = "Current job: " + currentJob;
</script>

</html>