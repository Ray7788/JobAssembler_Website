<?php
    require_once(__DIR__ . "/classes/database.php");
    $jobs = array();
    $pdo = Database::connect();
    $query = "SELECT Title, Name FROM JobPostings INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID ORDER BY JobPostings.JobID DESC LIMIT 5";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $jobs = $statement->fetchAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <!-- Bootstrap CSS 4.0.0-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/Index.css"> <!-- Include a custom CSS for the website. -->

    <title>JobAssembler</title>

    <style>
        * {
        margin: 0;
        padding: 0;
        border: 0;
        }

        /* Please Don't edit this part. For scroll Styling */
        main {   
            scroll-snap-type: y mandatory;
            overflow: scroll;
            height: 100vh;
        }

        /* Delete extra scroll bars */
        body {
            overflow: hidden;
        }

        th, td {   
            border-bottom: 2px solid #000000;
            border-left: 2px solid #000000;
            border-right: 2px solid #000000;
            background: white;
            color: black;
            width: 53%
        }

        table{
            margin-right: 25%;
            margin-left: 25%;
            border: 3px solid #000000;
        }

        /* For every pages */
        .container-fluid {
            width: 100vw;
            width: 100vh;
            padding:0%; 
            margin: 0%;
            scroll-snap-align: center;
        }

/* ----------------------------------------------------------------------------------------------------------------- */
/* For navbar */        
        /* For Logo border*/
        .d-inline-block-align-top {border-radius: 5px;}

        .container-fluid {
            scroll-snap-align: start;
            width: 100vw;
            height: 100vh;
        }
/* ----------------------------------------------------------------------------------------------------------------- */
        /* 1st page */
        .page1 {
            position: relative;
            width: 100vw;
            height: 120vh;
            background: linear-gradient(
                135deg,
            rgb(254,249,215),
            rgb(255,222,191),
            rgb(253,195,181),
            rgb(240,171,184));
            background-size: 200% 200%;
            animation: gradient-move 5s ease alternate infinite;
        }
        
         /*2nd page */
        .page2 {
            position: relative;
            width: 100vw;
            height: 120vh;
            border-width:0px ;
            background: linear-gradient(
            135deg,
            rgb(255,241,235),
            rgb(245,224,245),
            rgb(213,222,255),
            rgb(172,224,249));
            background-size: 200% 200%;
            animation: gradient-move 10s ease alternate infinite;
        }
        

        /* 3rd page */
        .page3 {
            position: relative;
            width: 100vw;
            height: 120vh;
            background: linear-gradient(
                135deg,
            rgb(248, 243, 151),
            rgb(255, 247, 96),
            rgb(170, 220, 94),
            rgb(155, 223, 70));
            background-size: 200% 200%;
            animation: gradient-move 10s ease alternate infinite;
        }

        /* Dynamic */
        @keyframes gradient-move {
        0% {
          background-position: 0% 0%;
        }
        100% {
          background-position: 100% 100%;
        }
    }
/* ----------------------------------------------------------------------------------------------------------------- */
        /* Text general styling  */
        .display-4, .display-6, .lead {text-align: center;}

/* ----------------------------------------------------------------------------------------------------------------- */
        </style>
    </head>


    <body>
    
    <!-- navbar -->
    <nav class="navbar navbar-light" style="background-color: white;">
        <a class="navbar-brand" href="index.php">
        <img src="Images/Logo1.png" width="30" height="30" class="d-inline-block-align-top" alt="";>
        JobAssembler
        <div class="nav-item">
            <a class="btn btn-success" href="SignUpPage2.php">Sign Up</a>
            <a class="btn btn-primary" href="login.php">Log in</a>
        </div>
        </a>
    </nav>
   

    <main>
    <!-- page1 -->
    <div class="container-fluid">
        <div class="page1">
            <br>
            <h1 class="display-4" style="color: #000000;"><b>Struggling with finding Employment or Employees?</b></h1>
            <br>
            <h1 class="display-6" style="color: #000000;">Ever wish it was as easy as Tinder?</h1>
            <hr class="my-3">
            <br>
            <p class="lead" style="color: #000000;">JobAssembler Is!</p> 
            <br>
            <a class="PageButton" style="color: red;" href="PrivacyPolicy.html">Privacy Policy</a>
        </div> 
    </div>

    <!-- page2 -->
    <div class="container-fluid">
            <div class="page2">
            <br>
            <h1 class="display-4" style="vertical-align:-webkit-baseline-middle; color: #FFFFCC"> <b>Direct access to Employees and Employers </b></h1>
            <br>
            <h1 class="display-6" style="color: #FFFFCC;">Ideal for New Graduates!</h1>
            <hr class="my-4">
            <br>
            <p class="lead" style="color: #FFFFCC;"><b>Easier and more engaging than the Competition</b></p>
            <br>
            <p class="lead" style="color: #FFFFCC;"><b>DON'T MISS OUT, JOIN NOW!</b></p>
        </div>
    </div>

    <!-- page3 -->
    <div class="container-fluid">
            <div class="page3">

            <h1 class="display-4" style="color: #000000;"><b>Our Newest Jobs:</b></h1>

            <table class="table" style="width: 50%;">
                    <thead style="border: 3px solid #000000;">
                        <tr>    
                            <th colspan="5" scope="col" style="padding: 15px; text-align: right" >Company</th>
                            <th colspan="5" scope="col" style="padding: 15px; text-align: left" >Job Title</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(count($jobs) == 0): ?>
                        <tr>
                            <th colspan="5" style="text-align: center;">No results found.</th>
                        </tr>
                    <?php else: ?>
                        <?php foreach($jobs as $num => $line): ?>
                        <tr>
                            <td colspan="5" style="text-align: right" ><?= $line["Name"]?></td>
                            <td colspan="5"style="text-align: left"><?= $line["Title"]?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
            </table>
            
           </div> 
    </div>

    </main>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 

    </body>
</html>
