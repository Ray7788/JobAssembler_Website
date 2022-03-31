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
if(!$user->is_authenticated()){
	header("Location: index.php");
	die(0);
}
$user->get_user();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Skills Form</title>
	<style>
    body {
        display: flex;
        justify-content: center;
        background: url("Images/pedro-lastra-Nyvq2juw4_o-unsplash.jpg") no-repeat
            center fixed;
        background-size: 100%;
		

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

		function getRightLevel(value){
			if(value == 0){
				return ' - None';
			}
			if(value >= 8){
				return ' - Advanced';
			}
			if(value >= 4){
				return ' - Intermediate';
			}
			else{
				return ' - Beginner';
			}
		}

		$(document).ready(function(){
			$("#userSkillsForm").submit(function(e){
				e.preventDefault();
				checkedArray = [];
				for(let i=0 ; i<8; i++){
					checkedArray.push(document.getElementById("soft" + i).checked);
				}
				//emoIntel, patience, adapt, projManage, probSolv, teamwork, interpersonal, leadership, time, decisiveness
				dataArray = {"userID":userID, "javaYears":slider1.value, "pythonYears":slider2.value, "csharpYears":slider3.value, "htmlYears":slider4.value, 
				"phpYears":slider5.value, "cssYears":slider6.value, "cplusYears":slider7.value, "sqlYears":slider8.value,
				"adapt":checkedArray[0], "projManage":checkedArray[1],
				"probSolv":checkedArray[2], "teamwork":checkedArray[3], "interpersonal":checkedArray[4],
				"leadership":checkedArray[5], "time":checkedArray[6], "decisiveness":checkedArray[7], "isCompany":"No", "jobID":"-1"};
				$.ajax({
					type:"POST",
					url:"api/skillAdding.php",
					data:dataArray,
					success:function(data){
						window.location = "EmployeeSwipeScreen.php";
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
			<a class="nav-link"  href="EmployeeSwipeScreen.php">Home</a>
			</li>
			<li class="nav-item  active">
			<a class="nav-link" href="EmployeeForm.php">Edit Details</a>
			</li>
			<li class="nav-item  active">
			<a class="nav-link" href="JobList.php">Job List</a>
			</li>
			<li class="nav-item  active">
			<a class="btn-danger"  style="margin-left: 30%; padding: 10px; white-space: nowrap;"  href="index.php">Log Out</a>
			</li>
		</form>
	</ul>
	</nav>

	<div class="container-fluid" style="margin-bottom: 100px">
    <div class="b">

<form id="userSkillsForm" name="UserSkills">
	<h1 class="display-1">Skill Form</h1>
    <br>
	<h5 class="d" >Please be honest when filling out this form to make sure you see the most suitable jobs. <br></h5>
	<hr>
	<h2>Programming languages experience: </h2>
	<h5>On a scale of 1-10, how much experience have you had with each of the below languages? Leave the scale at 0 if you have no experience in it that language.</h5>
	<br>
	<label for="Javaexp">Java Experience: </label>
	<input type="range" id="Javaexp" name="Javaexp" min="0" max="10" step="1" value="0">
	<p><span id="javaOut"></span></p>
	<script>
		var slider1 = document.getElementById("Javaexp");
		var output1 = document.getElementById("javaOut");
		output1.innerHTML = slider1.value + getRightLevel(0);

		slider1.oninput = function(){
			output1.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>

	<label for="Pythonexp">Python Experience: </label>
	<input type="range" id="Pythonexp" name="Pythonexp" min="0" max="10" step="1" value="0">
	<p><span id="pythonOut"></span></p>
	<script>
		var slider2 = document.getElementById("Pythonexp");
		var output2 = document.getElementById("pythonOut");
		output2.innerHTML = slider2.value + getRightLevel(0);

		slider2.oninput = function(){
			output2.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>
	<label for="Cexp">C# Experience: </label>
	<input type="range" id="Cexp" name="Cexp" min="0" max="10" step="1" value="0">
	<p><span id="c#Out"></span></p>
	<script>
		var slider3 = document.getElementById("Cexp");
		var output3 = document.getElementById("c#Out");
		output3.innerHTML = slider3.value + getRightLevel(0);

		slider3.oninput = function(){
			output3.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>
	<label for="HTMLexp">HTML Experience: </label>
	<input type="range" id="HTMLexp" name="HTMLexp" min="0" max="10" step="1" value="0">
	<p><span id="htmlOut"></span></p>
	<script>
		var slider4 = document.getElementById("HTMLexp");
		var output4 = document.getElementById("htmlOut");
		output4.innerHTML = slider4.value + getRightLevel(0);

		slider4.oninput = function(){
			output4.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>
	<label for="PHPexp">PHP Experience: </label>
	<input type="range" id="PHPexp" name="PHPexp" min="0" max="10" step="1" value="0">
	<p><span id="phpOut"></span></p>
	<script>
		var slider5 = document.getElementById("PHPexp");
		var output5 = document.getElementById("phpOut");
		output5.innerHTML = slider5.value + getRightLevel(0);

		slider5.oninput = function(){
			output5.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>
	<label for="CSSexp">CSS Experience: </label>
	<input type="range" id="CSSexp" name="CSSexp" min="0" max="10" step="1" value="0">
	<p><span id="cssOut"></span></p>
	<script>
		var slider6 = document.getElementById("CSSexp");
		var output6 = document.getElementById("cssOut");
		output6.innerHTML = slider6.value + getRightLevel(0);

		slider6.oninput = function(){
			output6.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>
	<label for="C++Exp">C++ Experience: </label>
	<input type="range" id="C++exp" name="C++exp" min="0" max="10" step="1" value="0">
	<p><span id="c++Out"></span></p>
	<script>
		var slider7 = document.getElementById("C++exp");
		var output7 = document.getElementById("c++Out");
		output7.innerHTML = slider7.value + getRightLevel(0);

		slider7.oninput = function(){
			output7.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>
	<label for="SQLExp">SQL Experience: </label>
	<input type="range" id="SQLExp" name="SQLExp" min="0" max="10" step="1" value="0">
	<p><span id="sqlOut"></span></p>
	<hr>
	<script>
		var slider8 = document.getElementById("SQLExp");
		var output8 = document.getElementById("sqlOut");
		output8.innerHTML = slider8.value + getRightLevel(0);

		slider8.oninput = function(){
			output8.innerHTML = this.value + getRightLevel(this.value);
		}
	</script>

	<h2>Soft Skills Checklist: </h2>
	<h5>Tick the box if you believe a skill applies to you</h5>
	<label for="adaptability">Adaptability: </label>
	<input type="checkbox" name="adaptability" id="soft0" value="adaptability">
	<br>
	<label for="projectManagement">Project Management: </label>
	<input type="checkbox" name="projectManagement" id="soft1" value="projectManagement">
	<br>
	<label for="probSolving">Problem Solving: </label>
	<input type="checkbox" name="probSolving" id="soft2" value="probSolving">
	<br>
	<label for="teamworkCollab">Teamworking and Collaboration: </label>
	<input type="checkbox" name="teamworkCollab" id="soft3" value="teamworkCollab">
	<br>
	<label for="interPersonal">Interpersonal Skills: </label>
	<input type="checkbox" name="interPersonal" id="soft4" value="interPersonal">
	<br>
	<label for="leadership">Leadership Skills: </label>
	<input type="checkbox" name="leadership" id="soft5" value="leadership">
	<br>
	<label for="timeManagement">Time Management: </label>
	<input type="checkbox" name="timeManagement" id="soft6" value="timeManagement">
	<br>
	<label for="decisiveness">Decisiveness: </label>
	<input type="checkbox" name="decisiveness" id="soft7" value="decisiveness">
	<br><hr>
	<!-- submit button -->
	<div class="d-grid gap-1">
	<input type="submit" class="btn btn-primary btn-lg btn-block" value="Submit">
	</div>  
	<br>
	<!-- cancel button -->
	<div class="d-grid gap-1">
	<input type="button" class="btn btn-primary btn-lg btn-block" value="Cancel" onclick="window.location.href='EmployeeSwipeScreen.php';" style="background-color:red;">
	</div>  
	</p>
	</form>
</body>
</html>