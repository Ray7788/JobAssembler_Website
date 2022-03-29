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

		$(document).ready(function(){
			$("#userSkillsForm").submit(function(e){
				e.preventDefault();
				var languageYears = [slider1.value, slider2.value, slider3.value, slider4.value, slider5.value, slider6.value, slider7.value, slider8.value];
				dataArray = {"userID":userID, "javaYears":slider1.value, "pythonYears":slider2.value, "csharpYears":slider3.value, "htmlYears":slider4.value, "phpYears":slider5.value, "cssYears":slider6.value, "cplusYears":slider7.value, "sqlYears":slider8.value};
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
	<div class="container-fluid" style="margin-bottom: 100px">
    <div class="b">

<form id="userSkillsForm" name="UserSkills">
	<h1 class="display-1">Skill Form</h1>
    <br>
	<h3 class="d" >Please be as honest as possible when filling out this form. <br></h3>
	<hr>
	<h3 class="d">Work experience:</h3>
	<textarea type="textbox" class ="e" id="Work Experience" name="Work Experience" rows="3" cols="60" placeholder="Please summarise in brief any work experience you have had"></textarea>
	<h2>Programming languages experience: </h2>
	<h3>Please select approxamitely how many years experience you have in each langauge</h3>
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
	<h3>Tick the box if you believe a skill applies to you</h3>
	<label for="emotionalIntelligence">Emotional Intelligence: </label>
	<input type="checkbox" name="emotionalIntelligence" id="emotionalIntelligence" value="emotionalIntelligence" style="text-align: center">
	<br>
	<label for="patience">Patience: </label>
	<input type="checkbox" name="patience" id="patience" value="patience">
	<br>
	<label for="adaptability">Adaptability: </label>
	<input type="checkbox" name="adaptability" id="adaptability" value="adaptability">
	<br>
	<label for="projectManagement">Project Management: </label>
	<input type="checkbox" name="projectManagement" id="projectManagement" value="projectManagement">
	<br>
	<label for="probSolving">Problem Solving: </label>
	<input type="checkbox" name="probSolving" id="probSolving" value="probSolving">
	<br>
	<label for="teamworkCollab">Teamworking and Collaboration: </label>
	<input type="checkbox" name="teamworkCollab" id="teamworkCollab" value="teamworkCollab">
	<br>
	<label for="interPersonal">Interpersonal Skills: </label>
	<input type="checkbox" name="interPersonal" id="interPersonal" value="interPersonal">
	<br>
	<label for="leadership">Leadership Skills: </label>
	<input type="checkbox" name="leadership" id="leadership" value="leadership">
	<br>
	<label for="timeManagement">Time Management: </label>
	<input type="checkbox" name="timeManagement" id="timeManagement" value="timeManagement">
	<br>
	<label for="decisiveness">Decisiveness: </label>
	<input type="checkbox" name="decisiveness" id="decisiveness" value="decisiveness">
	<br><hr>
     <button type="button" onclick="window.location.href='EmployeeForm.php';" class="button">Cancel</button>
     <input type="submit" value="Submit" class="button">
	</form>
</body>
</html>