<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Skills Form</title>
</head>
<body>
	<form id="userSkillsForm" name="UserSkills">
	<h1>User Skills Form</h1>
	<h3>Please be as honest as possible when filling out this form</h3>
	<hr>
	<label for="Work Experience">Work Experience: </label>
	<textarea id="Work Experience" name="Work Experience" rows="15" cols="40" placeholder="Please summarise in brief any work experience you have had"></textarea>
	<br><br>
	<h2>Programming language Experience: </h2>
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
	<h2>Soft Skills Checklist: </h2>
	<h3>Tick the box if you believe a skill applies to you</h3>
	<label for="emotionalIntelligence">Emotional Intelligence: </label>
	<input type="checkbox" name="emotionalIntelligence" id="emotionalIntelligence" value="emotionalIntelligence">
	<label for="patience">Patience: </label>
	<input type="checkbox" name="patience" id="patience" value="patience">
	<br><br>
	<label for="adaptability">Adaptability: </label>
	<input type="checkbox" name="adaptability" id="adaptability" value="adaptability">
	<label for="projectManagement">Project Management: </label>
	<input type="checkbox" name="projectManagement" id="projectManagement" value="projectManagement">
	<br><br>
	<label for="probSolving">Problem Solving: </label>
	<input type="checkbox" name="probSolving" id="probSolving" value="probSolving">
	<label for="teamworkCollab">Teamworking and Collaboration: </label>
	<input type="checkbox" name="teamworkCollab" id="teamworkCollab" value="teamworkCollab">
	<br><br>
	<label for="interPersonal">Interpersonal Skills: </label>
	<input type="checkbox" name="interPersonal" id="interPersonal" value="interPersonal">
	<label for="leadership">Leadership Skills: </label>
	<input type="checkbox" name="leadership" id="leadership" value="leadership">
	<br><br>
	<label for="timeManagement">Time Management: </label>
	<input type="checkbox" name="timeManagement" id="timeManagement" value="timeManagement">
	<label for="decisiveness">Decisiveness: </label>
	<input type="checkbox" name="decisiveness" id="decisiveness" value="decisiveness">
	<br><br><hr>
     <button type="button" onclick="window.location.href='SignUpPage2.php';" class="button">Cancel</button>
     <input type="submit" value="Submit" class="button">
	</form>
</body>
</html>