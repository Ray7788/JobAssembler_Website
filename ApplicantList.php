<?php
require_once(__DIR__ . "/classes/database.php");
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
    header("Location: index.php");
    die(0);
}
if (isset($_REQUEST["page"])) {
    try {
        $page = intval($_REQUEST["page"]);
    }
    catch (Exception $e) {
        $page = 1;
    }
}
else {
    $page = 1;
}
if ($page < 1) {
    $page = 1;
}
$offset = 0;
const RESULTS_PER_PAGE = 20;
$offset = ($page - 1) * RESULTS_PER_PAGE;
if ($offset < 0) {
    $offset = 0;
}

$jobs = array();
$pdo = Database::connect();
$query = "SELECT UserAccounts.Username, UserAccounts.Forename, UserAccounts.Surname, UserAccounts.Biography, JobPostings.Title, UserJobs.UserAccepted, UserJobs.CompanyAccepted
FROM JobPostings INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID
INNER JOIN UserAccounts ON UserJobs.UserID = UserAccounts.UserID
WHERE JobPostings.CompanyID = ? LIMIT " . RESULTS_PER_PAGE . " OFFSET " . $offset;
$statement = $pdo->prepare($query);
$statement->execute([$user->company_id]);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
<main>
    <div class="container" style="padding-top: 2rem">
        <a class="btn btn-secondary" href="main.php">Back</a>
        <h1>Your viewed applicants</h1>
        <h4 class="text-muted">All the applicants you have either accepted or declined can be seen here</h4>
        <br/>
        <table class="table" style="margin: auto;">
            <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Forename</th>
                    <th scope="col">Surname</th>
                    <th scope="col">About</th>
                    <th scope="col">Job Title</th>
                    <th scope="col">Accepted</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php if(count($jobs) == 0): ?>
                <tr>
                    <th colspan="7" style="text-align: center;">No results found.</th>
                </tr>
            <?php else: ?>
                <?php foreach($jobs as $num => $line): ?>
                <tr>
                    <td><?= $line["Username"]?></td>
                    <td><?= $line["Forename"]?></td>
                    <td><?= $line["Surname"]?></td>
                    <td style="word-break:break-word;"><?= $line["Biography"]?></td>
                    <td><?= $line["Title"]?></td>
                    <td><input type="checkbox" disabled <?= $line["CompanyAccepted"] ? "checked": ""?>></td>
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
            <?php endif; ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item<?=$page == 1 ? " disabled" : ""?>"><a class="page-link"<?=$page != 1 ? " href=\"?page=". $page - 1 ."\"" : ""?><?=$page == 1 ? " disabled" : ""?>>Previous</a></li>
                <?php if ($page != 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?=$page-1?>"><?=$page-1?></a></li>
                <?php endif; ?>
                <li class="page-item active"><a class="page-link"><?=$page?></a></li>
                <li class="page-item"><a class="page-link" href="?page=<?=$page+1?>"><?=$page+1?></a></li>
                <li class="page-item"><a class="page-link" href="?page=<?=$page+1?>">Next</a></li>
            </ul>
        </nav>
    </div>
</main>
</body>
</html>