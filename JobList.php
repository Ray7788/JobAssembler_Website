<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    die(0);
}
$user = $_SESSION["user"];
if (!$user->is_authenticated()) {
    header("Location: index.php");
    die(0);
}
$jobs = array();
$pdo = Database::connect();
$query = "SELECT JobPostings.*, UserJobs.UserAccepted, UserJobs.CompanyAccepted FROM JobPostings INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID WHERE UserJobs.UserID = ? LIMIT 20";
$statement = $pdo->prepare($query);
$statement->execute([$user->user_id]);
$jobs = $statement->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Viewed Jobs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<main class="container" style="padding-top: 2rem">
    <h1>Your viewed jobs</h1>
    <h4 class="text-muted">All the jobs you have either accepted or declined can be seen here</h4>
    <br/>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th scope="col">Company</th>
                <th scope="col">Job Title</th>
                <th scope="col">About</th>
                <th scope="col">Location</th>
                <th scope="col">Accepted</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($jobs as $num => $line): ?>
            <tr>
                <td><?= $line["JobID"]?></td>
                <td><?= $line["Title"]?></td>
                <td style="word-break:break-all;"><?= $line["Details"]?></td>
                <td><?= "Unknown"?></td>
                <td><input type="checkbox" disabled <?= $line["UserAccepted"] ? "checked": ""?>></td>
                <td>
                    <div class="dropdown show">
                        <button class="btn btn-sm dropdown-toggle" href="" role="button" id="dropdownMenuLink<?=$num?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink<?=$num?>">
                            <button class="dropdown-item btn" href="">View details</button>
                            <button class="dropdown-item btn" href="">Change acceptance</button>
                            <button class="dropdown-item btn btn-danger" href="">Report</button>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>