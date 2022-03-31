<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
require_once(__DIR__ . "/classes/company.php");
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
$query = "SELECT JobPostings.*, UserJobs.UserAccepted, UserJobs.CompanyAccepted, UserJobs.CompanySeen, Companies.Name FROM JobPostings INNER JOIN UserJobs ON JobPostings.JobID = UserJobs.JobID INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID WHERE UserJobs.UserID = ? AND UserJobs.UserSeen = 1 LIMIT " . RESULTS_PER_PAGE . " OFFSET " . $offset;
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
    <title>Viewed Jobs - JobAssembler</title>

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
        var map;
        var marker;
        $(function () {
            map = L.map("map").setView({lat: 53.4808, lng: -2.2426}, 13);
            marker = L.marker({lat: 0, lng: 0});
            map.invalidateSize();

            // add the OpenStreetMap tiles
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: "&copy; <a href=\"https://openstreetmap.org/copyright\">OpenStreetMap contributors</a>"
            }).addTo(map);

            // show the scale bar on the lower left corner
            L.control.scale({imperial: true, metric: true}).addTo(map);
        })
    </script>
    
    <script>
        $(function () {
            $("#jobDetailsModal").on("show.bs.modal", function (event) {
                let button = $(event.relatedTarget) // Button that triggered the modal
                let id = button.data("job-id") // Extract info from data-* attributes
                $.ajax({
                    type:"GET",
                    url:"api/jobDetails.php?id=" + id,
                    success: function(data){
                        let modal = $(this);
                        $("#jobDetailsTitle").text(data["Title"]);
                        $("#jobDetailsText").text(data["Details"]);
                        if (data["CompanyImage"] != null) {
                            $("#jobDetailsImage").attr("src", data["CompanyImage"]);
                        }
                        else {
                            $("#jobDetailsImage").attr("src", "Images/Logo1.png");
                        }
                        $("#jobDetailsCompanyName").text(data["Name"]);
                        $("#jobDetailsCompanyDescription").text(data["Description"]);
                        if (data["Latitude"] != null && data["Longitude"] != null) {
                            $("#mapSection").show();
                            map.setView({lat: data["Latitude"], lng: data["Longitude"]}, 17);
                            marker.remove();
                            marker.setLatLng({lat: data["Latitude"], lng: data["Longitude"]}).bindPopup(`<a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(data["Latitude"] + "," + data["Longitude"])}">Latitude: ${data["Latitude"]}, Longitude: ${data["Longitude"]}</a>`).addTo(map);
                        } else {
                            $("#mapSection").hide();
                        }
                        console.log(data);
                    },
                    error: function(xhr){
                        let response = xhr.responseJSON
                        if (response.hasOwnProperty("message")) {
                            alert("Error:\n" + response["message"]);
                        } else {
                            alert("An unknown error occurred. Please try again later.")
                        }
                    }
                })
            });
            $("#jobDetailsModal").on("shown.bs.modal", function (event) {
                map.invalidateSize();
            });
            $("#jobUpdateModal").on("show.bs.modal", function (event) {
                let button = $(event.relatedTarget) // Button that triggered the modal
                let id = button.data("job-id") // Extract info from data-* attributes
                console.log(id)
                $.ajax({
                    type:"GET",
                    url:"api/jobDetails.php?id=" + id,
                    success: function(data){
                        $("#jobUpdateModal").data("job-id", id)
                        $("#jobUpdateText").text(data["Name"] + ": " + data["Title"]);
                        console.log(data);
                    },
                    error: function(xhr){
                        alert("error\n" + xhr.responseJson);
                    }
                })
            })
            $("#jobUpdateAccept").on("click", function (event) {
                    let modal = $("#jobUpdateModal");
                    let id = modal.data("job-id");
                    $.ajax({
                        type:"POST",
                        url:"api/jobUpdating.php?userID=<?=$user->user_id?>&userAccepted=1&jobID=" + id,
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
                    let id = modal.data("job-id");
                    $.ajax({
                        type:"POST",
                        url:"api/jobUpdating.php?userID=<?=$user->user_id?>&userAccepted=0&jobID=" + id,
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
            background: url("Images/pedro-lastra-Nyvq2juw4_o-unsplash.jpg") no-repeat
            center fixed;
            }
        .container{
            background: white;
            position:relative;
            margin-top: 100px;
            margin-bottom: 50px;
            border-radius: 15px;
        }

        .php {
            color: #e9ecef;
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
        echo("You are signed in as: &nbsp;" . $user->username);
    ?>
    </span> 

    <ul class="navbar-nav" >

        <form class="form-inline">
            <li class="nav-item  active">
            <a class="nav-link" href="EmployeeSwipeScreen.php">Home</a>
            </li>
            <li class="nav-item  active">
            <a class="nav-link"  href="userSkills.php">My Skills</a>
            </li>
            <li class="nav-item  active">
            <a class="nav-link" href="EmployeeForm.php">Edit Details</a>
            </li>
            <li class="nav-item  active">
            <a class="btn-danger"  style="margin-left: 30%; padding: 10px; white-space: nowrap;"  href="index.php">Log Out</a>
            </li>
        </form>
    </ul>
    
    </nav>


    <main class="container" style="padding-top: 2rem">
    <div style="text-align: right;">
        <a class="btn btn-secondary" href="main.php"><i class="bi bi-arrow-left-circle-fill"></i> Back</a>
    </div>
    <h1 style= "font-family: Helvetica">Your viewed jobs</h1>
    <h4 class="text-muted">All the jobs you have either accepted or declined can be seen here</h4>
    <br/>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Company</th>
                <th scope="col">Job Title</th>
                <!--<th scope="col">About</th>-->
                <th scope="col">Location</th>
                <th scope="col">Accepted</th>
                <th scope="col">Company Accepted</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($jobs) == 0): ?>
            <tr>
                <th colspan="5" style="text-align: center;">No results found.</th>
            </tr>
        <?php else: ?>
            <?php foreach($jobs as $num => $line): ?>
                <?php
                if (!$line["CompanySeen"]) {
                    $companyAcceptedText = "Not Seen";
                    $companyAcceptedStyle = "text-secondary";
                }
                else {
                    $companyAcceptedText = $line["CompanyAccepted"] ? "Accepted": "Declined";
                    $companyAcceptedStyle = $line["CompanyAccepted"] ? "text-success" : "text-danger";
                }
                ?>
            <tr>
                <td><?= $line["Name"]?></td>
                <td><?= $line["Title"]?></td>
                <!--<td style="word-break:break-all;"><?= $line["Details"]?></td>-->
                <td><?= $line["RemoteJob"] ? "Remote" : (is_null($line["Latitude"]) || is_null($line["Longitude"]) ? "None Given" : "See Details") ?></td>
                <td class="<?= $line["UserAccepted"] ? "text-success" : "text-danger" ?>"><?= $line["UserAccepted"] ? "Accepted": "Declined"?></td>
                <td class="<?= $companyAcceptedStyle ?>"><?= $companyAcceptedText?></td>
                <td>
                    <div class="dropdown show">
                        <button class="btn btn-sm dropdown-toggle" href="" role="button" id="dropdownMenuLink<?=$num?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink<?=$num?>">
                            <button class="dropdown-item btn" data-toggle="modal" data-target="#jobDetailsModal" data-job-id="<?=$line["JobID"]?>">View details</button>
                            <button class="dropdown-item btn" data-toggle="modal" data-target="#jobUpdateModal" data-job-id="<?=$line["JobID"]?>">Change acceptance</button>
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
                    <p id="jobDetailsText" style="word-break: break-word;"></p>
                </div>
                <div class="modal-body" id="mapSection" style="border-top: 1px solid #e9ecef">
                    <div id="map" style="height: 200px;"></div>
                </div>
                <div class="modal-body" style="border-top: 1px solid #e9ecef">
                    <img id="jobDetailsImage" style="width: 100px; height: 100px; float: left; margin: 10px;" src="Images/Logo1.png" class="rounded float-left"/>
                    <div>
                        <h6 id="jobDetailsCompanyName">Unknown</h6>
                        <p id="jobDetailsCompanyDescription">Unknown</p>
                    </div>
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
                    <h5 class="modal-title" id="jobUpdateTitle">Change job acceptance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 id="jobUpdateText" style="word-break: break-word;"></h6>
                    <p>Change job acceptance status?</p>
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