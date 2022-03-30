<?php
require_once(__DIR__ . "/classes/user.php");
require_once(__DIR__ . "/classes/company.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    die(0);
}
$user = $_SESSION["user"];
if (!$user->is_authenticated()) {
    header("Location: index.php");
    die(0);
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Creation - JobAssembler</title>

   <!-- CSS Styling-->
    <link rel="stylesheet" href="CSS/JobCreation.css">
    <link rel="stylesheet" href="../JobAssembler/CSS/JobCreation.css">
    <!-- Bootstrap CSS 5.1.3-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Leafletjs (map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script>
        // Shamelessly stole this directly from James's code
        $(document).ready(function(){
            $("#jobCreateForm").submit(function (e){
                e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                $.ajax({
                    type:"POST",
                    url:"api/jobCreate.php",
                    data: $(this).serialize(),
                    success: function(data){
                        window.location = "main.php"  //Where to go if successful
                    },
                    error: function(xhr){
                        var obj = xhr.responseJSON;
                        if(Object.keys(obj).includes("message")){
                            warning.innerHTML = obj["message"];
                        }else{
                            warning.innerHTML = "An unknown error has occurred. Please try again later.";
                        }
                    }
                })
            });})
    </script>
    <script>
        var map;
        var marker = L.marker({lat: 0, lng: 0});
        $(function() {
            map = L.map("map").setView({lat: 53.4808, lng: -2.2426}, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
            }).addTo(map);

            // show the scale bar on the lower left corner
            L.control.scale({imperial: true, metric: true}).addTo(map);

            // show a marker on the map
            //L.marker({lon: 0, lat: 0}).bindPopup('The center of the world').addTo(map);

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
        width: 550px;
        height: 850px;
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
        font: 900 16px '';
    }

        /* For the specialised button */
/* .btn btn-primary btn-lg {
    width: 15vw;
    height: 20vh;
    border-radius: 30px;
    line-height: 100px;
    text-align: center;
    text-decoration: none;
    font-size: 28px;
    color: #fff;  
    background-image: linear-gradient(90deg, #03a9f4, #f441a5, #ffeb3b, #09a8f4);
    background-size: 400%;
    z-index: 1;

  }


  a.btn btn-primary btn-lg {
    animation: sun 8s infinite;
    }

  a.btn btn-primary btn-lg:hover::before {
    animation: sun 8s infinite;
    }

  @keyframes sun {
  100% {
      background-position: -400% 0;
      }
  } */
    

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
                echo("You are signed in as: &nbsp;" . $user->username . "&nbsp &nbsp");
            ?>
        </span>   
        <ul class="navbar-nav" style="margin-left: 10%;">
                <!-- Links -->
                <a class="nav-link" href="EmployerSwipeScreen.php" style="margin-left:5%; white-space: nowrap;">Home</a>
                <a class="nav-link" href="JobSkills.php" style="margin-left:5%; white-space: nowrap;">Job Skills</a>
                <a class="nav-link" href="ApplicantList.php" style="margin-left:5%; white-space: nowrap;">Applicant List</a>
                <a class="nav-link" href="CompanyAddUsers.php" style="margin-left:5%; white-space: nowrap;">Add Users</a>
                <a class="btn-danger" style="margin-left: 30%; padding: 10px; white-space: nowrap;"  href="index.php" >Log Out</a>            
        </ul>
</nav>

    <div class="container-fluid" style="margin-bottom: 100px">
    <div class="b">

    <form name="jobCreateForm" id="jobCreateForm">
        <p>
        <<a class="btn" href="EmployerSwipeScreen.php">Back</a>
        <h1 class="display-1">Enter Job Detail</h1>
        <br>

        <h3 class="d">Job Title<br></h3>
        <input type="text" name="title" class="e" id="title"  placeholder="Input Title" required>
        <br><br>
        <h3 class="d">Job Description</h3>
        <textarea type="textbox" name="description" class="e" id="description" rows="3" cols="50" placeholder="More Details        " required></textarea>
        <br><br>
        <h3 class="d">Job Location</h3>
        <!-- map -->
        <div id="map" style="height: 200px"></div>
        <button id="resetMap" class="btn btn-danger btn-sm" style="margin: 10px 0px;" type="button">Remove location</button>
        <br><br>
        <input type="hidden" name="latitude" id="latitude"/>
        <input type="hidden" name="longitude" id="longitude"/>
        <!-- button -->
        <div class="d-grid gap-1">
        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Submit">
        </div>  
        </p>
       
        
    </form>
    
    </div>
    </div>

    <!-- <p class="mt-5 mb-3 text-muted">&copy; X17 2021-2022</p> -->
</body>
</html>