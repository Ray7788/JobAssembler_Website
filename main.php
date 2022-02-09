<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Job Assembler</title>
</head>
<body>
	<h1>Job Assembler</h1>
	<?php
		$name = $_POST['username'];
        $pass = $_POST['password'];
        if ($name == 'Dennis' and $pass == 'DennisPassword123') {
            echo ("Successfully logged in.");
        } else {
            echo ("Incorrect, please try again.");
        }
	?>
</body>
</html>
