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
$user->get_user();
if ($user->company_id < 1) {
    header("Location: main.php");
    die(0);
}
if (isset($_REQUEST["page"])) {
    try {
        $page = intval($_REQUEST["page"]);
    }
    catch (Throwable $e) {
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
$query = "SELECT UserAccounts.Username, UserAccounts.Forename, UserAccounts.Surname, UserAccounts.Biography, JobPostings.Title, UserJobs.UserAccepted, UserJobs.CompanyAccepted, JobPostings.JobID, UserAccounts.UserID
FROM JobPostings INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID
INNER JOIN UserAccounts ON UserJobs.UserID = UserAccounts.UserID
WHERE JobPostings.CompanyID = ? AND UserJobs.CompanySeen = 1 LIMIT " . RESULTS_PER_PAGE . " OFFSET " . $offset;
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
    <title>Job Applicants - Job Assembler</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Leafletjs (map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script>
        $(function () {
            $("#jobDetailsModal").on("show.bs.modal", function (event) {
                let button = $(event.relatedTarget) // Button that triggered the modal
                let id = button.data("user-id") // Extract info from data-* attributes
                $.ajax({
                    type:"GET",
                    url:"api/userDetails.php?id=" + id,
                    success: function(data){
                        let modal = $(this);
                        $("#jobDetailsTitle").text(data["Username"]);
                        if (data["ProfileImage"] != null) {
                            $("#userDetailsImage").attr("src", data["ProfileImage"]);
                        }
                        else {
                            $("#userDetailsImage").attr("src", "Images/Logo1.png");
                        }
                        $("#userDetailsName").text(data["Forename"] + " " + data["Surname"]);
                        $("#userDetailsDescription").text(data["Biography"]);
                        if (data["Email"] == "" || data["Email"] == null) {
                            $("#userDetailsEmail").attr("href", "#");
                            $("#userDetailsEmail").text("Not Given");
                        } else {
                            $("#userDetailsEmail").attr("href", "mailto:" + data["Email"]);
                            $("#userDetailsEmail").text(data["Email"]);
                        }
                        /**(data["Latitude"] != null && data["Longitude"] != null) {
                            $("#mapSection").show();
                            map.setView({lat: data["Latitude"], lng: data["Longitude"]}, 17);
                            marker.remove();
                            marker.setLatLng({lat: data["Latitude"], lng: data["Longitude"]}).bindPopup(`Latitude: ${data["Latitude"]}, Longitude: ${data["Longitude"]}`).addTo(map);
                        } else {
                            $("#mapSection").hide();
                        }**/
                        console.log(data);
                    },
                    error: function(xhr){
                        alert("error\n" + xhr.responseJson);
                    }
                })
            });
            /**$("#jobDetailsModal").on("shown.bs.modal", function (event) {
                map.invalidateSize();
            });**/
            $("#jobUpdateModal").on("show.bs.modal", function (event) {
                let button = $(event.relatedTarget) // Button that triggered the modal
                let id = button.data("user-id") // Extract info from data-* attributes
                let jobId = button.data("job-id")
                console.log(id)
                $.ajax({
                    type:"GET",
                    url:"api/userDetails.php?id=" + id,
                    success: function(data){
                        $("#jobUpdateModal").data("user-id", id)
                        $("#jobUpdateModal").data("job-id", jobId)
                        $("#jobUpdateText").text(data["Username"] + ": " + data["Forename"] + " " + data["Surname"]);
                        console.log(data);
                    },
                    error: function(xhr){
                        alert("error\n" + xhr.responseJson);
                    }
                })
            })
            $("#jobUpdateAccept").on("click", function (event) {
                let modal = $("#jobUpdateModal");
                let id = modal.data("user-id");
                let jobId = modal.data("job-id");
                $.ajax({
                    type:"POST",
                    url:"api/jobUpdating.php?userID=" + id + "&companyAccepted=1&jobID=" + jobId,
                    success: function(data){
                        window.location.reload();
                    },
                    error: function(xhr){
                        alert("error\n" + xhr.responseJson);
                    }
                })
                }
            )
            $("#jobUpdateDecline").on("click", function (event) {
                let modal = $("#jobUpdateModal");
                let id = modal.data("user-id");
                let jobId = modal.data("job-id");
                $.ajax({
                    type:"POST",
                    url:"api/jobUpdating.php?userID=" + id + "&companyAccepted=0&jobID=" + jobId,
                    success: function(data){
                        window.location.reload();
                    },
                    error: function(xhr){
                        alert("error\n" + xhr.responseJson);
                    }
                })
                }
            )
        })
    </script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url("Images/pexels-burst-374870.jpg");
		    background-repeat:repeat-x;
            }

        .container{
            background: white;
            position:relative;
            margin-top: 100px;
            margin-bottom: 50px;
        }

        /* For navbar */        
        /* For Logo border*/
        .d-inline-block-align-top {border-radius: 5px;}
       
        .navbar-nav{
            position:absolute;
            right: 50px;
        }
    </style>
</head>
<body>
  <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <!-- Brand LOGO -->
        <a class="navbar-brand">
            <img src="Images/Logo1.png" width="30" height="30" class="d-inline-block-align-top" alt="Logo";>
        </a>
        <span class="navbar-text">
            <?php
                echo("You are signed in as:&nbsp;" . $user->username . "&nbsp &nbsp");
            ?>
        </span>   

        <ul class="navbar-nav" >    
            <!-- Links -->
            
            <form class="form-inline">
            
            <li class="nav-item">
            <a class="nav-link" href="EmployerSwipeScreen.php" style="margin-left:5%; white-space: nowrap;">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="JobCreation.php" style="margin-left:5%; white-space: nowrap;;">Job Creation</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="CompanyJobs.php" style="margin-left:5%; white-space: nowrap;;">Job Postings</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="JobSkills.php" style="margin-left:5%; white-space: nowrap;">Job Skills</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="CompanyAddUsers.php" style="margin-left:5%; white-space: nowrap;">Add Users</a>
            </li>
            <li class="nav-item">
            <a class="btn-danger" style="margin-left: 30%; padding: 10px; white-space: nowrap;"  href="api/logout.php" >Log Out</a>     
            </li> 
                 
            </form>
            <!-- </div> -->
        </ul>
        
        </nav>


<main class="container" style="padding-top: 2rem">
    <div style="text-align: right;">
        <a class="btn btn-secondary" href="main.php"><i class="bi bi-arrow-left-circle-fill"></i> Back</a>
    </div>
    <h1>Your viewed applicants</h1>
    <h4 class="text-muted">All the applicants you have either accepted or declined can be seen here</h4>
    <br/>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col">Forename</th>
                <th scope="col">Surname</th>
                <th scope="col">Job Title</th>
                <th scope="col">Accepted</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($jobs) == 0): ?>
            <tr>
                <th colspan="6" style="text-align: center;">No results found.</th>
            </tr>
        <?php else: ?>
            <?php foreach($jobs as $num => $line): ?>
            <tr>
                <td><?= $line["Username"]?></td>
                <td><?= $line["Forename"]?></td>
                <td><?= $line["Surname"]?></td>
                <td><?= $line["Title"]?></td>
                <td class="<?= $line["CompanyAccepted"] ? "text-success" : "text-danger" ?>"><?= $line["CompanyAccepted"] ? "Accepted": "Declined"?></td>
                <td>
                    <div class="dropdown show">
                        <button class="btn btn-sm dropdown-toggle" href="" role="button" id="dropdownMenuLink<?=$num?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink<?=$num?>">
                            <button class="dropdown-item btn" data-toggle="modal" data-target="#jobDetailsModal" data-user-id="<?=$line["UserID"]?> data-job-id="<?=$line["JobID"]?>">View details</button>
                            <button class="dropdown-item btn" data-toggle="modal" data-target="#jobUpdateModal" data-user-id="<?=$line["UserID"]?>" data-job-id="<?=$line["JobID"]?>">Change acceptance</button>
                            <button class="dropdown-item btn btn-danger" href="">Report</button>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <nav style="padding-bottom: 10px">
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
    <div class="modal fade" tabindex="-1" role="dialog" id="jobDetailsModal" aria-labelledby="jobDetailsTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobDetailsTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="userDetailsImage" style="width: 100px; height: 100px; float: left; margin: 10px;" src="Images/Logo1.png" class="rounded float-left"/>
                    <div>
                        <h6 id="userDetailsName">Unknown</h6>
                        <p id="userDetailsDescription">Unknown</p>
                        <p style="word-wrap: break-word"><span style="font-weight: bold">Email Address:</span> <a id="userDetailsEmail">Unknown</a></p>
                    </div>
                </div>
                <div class="modal-body" id="mapSection" style="border-top: 1px solid #e9ecef" hidden>
                    <div id="map" style="height: 200px;"></div>
                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="jobUpdateModal" aria-labelledby="jobUpdateTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobUpdateTitle">Change applicant acceptance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 id="jobUpdateText" style="word-break: break-word;"></h6>
                    <p>Change applicant acceptance status?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="jobUpdateAccept">Accept job</button>
                    <button type="button" class="btn btn-danger" id="jobUpdateDecline">Decline job</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>