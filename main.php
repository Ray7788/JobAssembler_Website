<?php
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    header("Location: index.php");
    die(0);
}
$user = $_SESSION["user"];
if (!$user->is_authenticated()) {
    header("Location: index.php");
    die(0);
}
if ($user->company_id == -1) {
    header("Location: EmployeeSwipeScreen.php");
}
else if ($user->company_id == 0) {
    header("Location: JoinCompany.php");
}
else {
    header("Location: EmployerSwipeScreen.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Job Assembler</title>
</head>
<body>
	<h1>Job Assembler</h1>

</body>
</html>
