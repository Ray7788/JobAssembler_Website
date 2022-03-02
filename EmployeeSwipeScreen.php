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
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employee Swipe Screen</title>
        <style type="text/css">
            
        </style>
    </head>
    <body>
        <?php
            $user = $_SESSION["user"];
            echo("USER " . $user->username);
        ?>
    </body>

</html>