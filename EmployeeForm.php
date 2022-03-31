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
?>
<!DOCTYPE html>

<html>
<head>
 <title>Employee Form</title>
    <style>
    </style>
    <!-- Bootstrap CSS 4.6.1-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Leafletjs (map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script>
        $(function(){
                $("#employeeForm").on("submit", function(event){
                        event.preventDefault();
                        /**let formData = new FormData();
                        formData.append("email", $("#email").val());
                        formData.append("biography", $("#biography").val());
                        formData.append("profilePic", )**/
                        $.ajax({
                                type:"POST",
                                url:"api/EmployeeRegister.php",
                                enctype:"multipart/form-data",
                                data: new FormData($(this)[0]),
                                contentType: false,
                                processData: false,
                                success: function(data){
                                        window.location.reload();
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
                })
        })
    </script>

    <script>
        var map;
        var marker = L.marker({lat: <?=$user->latitude?>, lng: <?=$user->longitude?>});
        $(function() {
            map = L.map("map").setView({lat: 53.4808, lng: -2.2426}, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
            }).addTo(map);
            <?php if ($user->latitude != 0 && $user->longitude != 0) {echo "marker.addTo(map);";}?>

            // show the scale bar on the lower left corner
            L.control.scale({imperial: true, metric: true}).addTo(map);

            map.on("click", function(e) {
                let coords = map.mouseEventToLatLng(e.originalEvent);
                marker.remove();
                marker.setLatLng({lat: coords.lat, lng: coords.lng}).addTo(map);
                $("#latitude").val(coords.lat);
                $("#longitude").val(coords.lng);
            })
        })
        $(function() {
            $("#resetMap").on("click", function() {
                marker.remove();
                $("#latitude").removeAttr("value");
                $("#longitude").removeAttr("value");
            })
        })
    </script>

<style>
    body {
        display: flex;
        justify-content: center;
        background-image: linear-gradient(to left, #e1eec3, #f05053);

    }
    .container-fluid{
        background-color: #fff;
        width: 50%;
        height: 80%;
        position: relative;
        display: flex;
        border-radius: 10px;
        justify-content: center;
        align-items: center;
        vertical-align: center;
        margin-top: 5%;
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
            <a class="nav-link"  href="EmployeeSwipeScreen.php">Home</a>
            </li>
            <li class="nav-item  active">
            <a class="nav-link" href="userSkills.php">My Skills</a>
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

<div class="container-fluid">


    <form id="employeeForm" name="employeeForm" enctype="multipart/form-data" action="api/EmployeeRegister.php" method="post" style="padding: 5%;">
        <h2>Edit information</h2>
        <hr>
        <label for="email">Email: </label>
        <input type="text" name="email" placeholder="Enter Email" id="email" class="inputBox" value="<?=$user->email?>">
        <br><br>
        <label for="biography">About you: </label>
        <textarea type="text" name="biography" placeholder="Enter any relevant information about yourself" id="biography" class="inputBox" value="<?=$user->biography?>"></textarea>
        <br><br>
        <label for="profilePic">Profile Pic: </label>
        <input type="file" id="profilePic" name="profilePic" accept="image/*">
        <br><br>
        <div id="map" style="height: 200px"></div>
        <button id="resetMap" class="btn btn-danger btn-sm" style="margin: 10px 0px;" type="button">Remove location</button>
        <input type="hidden" name="latitude" id="latitude" value="<?=$user->latitude?>"/>
        <input type="hidden" name="longitude" id="longitude" value="<?=$user->longitude?>"/>
        <br><br>
        <div class="form-check">
            <input type="hidden" name="remote" value/>
            <input class="form-check-input" type="checkbox" id="remote" name="remote" value="remote" <?=$user->remote ? "checked" : ""?>/>
            <label class="form-check-label" for="remote">Working remotely</label>
        </div>
        <!-- button -->
        <p>By Creating an account you agree to our <a href="PrivacyPolicy.html">Terms and Conditions</a><p>
        <button type="button" onclick="window.location.href='main.php';" class="button">Cancel</button>
        <input type="submit" value="Confirm" class="button">
    </form>
</div>
</body>

</html>
