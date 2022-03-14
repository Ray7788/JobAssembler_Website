<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <!-- Bootstrap CSS -->
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

        /* For every pages */
        .container-fluid {
            width: 100vw;
            width: 100vh;
            padding:0%; 
            margin: 0%;
            scroll-snap-align: center;
        }

/* ----------------------------------------------------------------------------------------------------------------- */
        /* For Logo border*/
        .d-inline-block-align-top {border-radius: 5px;}

        /* For navbar */
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
            border-width:0px ;
            background: linear-gradient(
            135deg,
            hsl(170deg, 80%, 70%),
            hsl(190deg, 80%, 70%),
            hsl(250deg, 80%, 70%),
            hsl(320deg, 80%, 70%));
            background-size: 200% 200%;
            animation: gradient-move 10s ease alternate infinite;
        }
        
         /*2nd page */
        .page2 {
            position: relative;
            width: 100vw;
            height: 120vh;
            background: linear-gradient(
                135deg,
            rgb(240, 117, 199),
            rgb(241, 173, 255),
            rgb(221, 102, 195),
            rgb(241, 185,93));
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
        /*Button position  */
       .PageButton{position: absolute; left: 50%; top: 60%; transform: translate(-50%,-50%)}

    </style>
 </head>


    <body>
    <!-- Image and text -->
    
    <!-- navbar -->
    <nav class="navbar navbar-light" style="background-color: white;">
        <a class="navbar-brand" href="index.php">
        <img src="Images/Logo1.png" width="30" height="30" class="d-inline-block-align-top" alt="";>
        JobAssembler
        <div class="nav-item">
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
            <div class="PageButton">
            <a class="btn-btn-primary-btn-lg" id="SignUpButton1" href="SignUpPage2.php" role="button">Sign Up Now!</a>
            </div> 
        </div> 
    </div>

    <!-- page2 -->
    <div class="container-fluid">
            <div class="page2">
                <br>
            <h1 class="display-4" style="vertical-align:-webkit-baseline-middle; color: #FFFFCC"> <b>Direct Contact Between Employers and Employees  :)</b></h1>
            <br>
            <h1 class="display-6" style="color: #FFFFCC;">Customized for campus students!</h1>
            <hr class="my-4">
            <p class="lead" style="color: #FFFFCC;"><b>Easier and more engaging than existing options (LinkedIn, Indeed, Reed ... etc.)</b></p>
            <div class="PageButton">
            <a class="btn-btn-primary-btn-lg" id="SignUpButton2" href="SignUpPage2.php" role="button">Sign Up Now!</a>
            </div>
        </div>
    </div>

    <!-- page3 -->
    <div class="container-fluid">
            <div class="page3">
                <br>
            <h1 class="display-4" style="color: #7da2a9;">The Best of the Best</h1>
            <div class="PageButton">  
            <a class="btn-btn-primary-btn-lg" id="SignUpButton3" href="SignUpPage2.php" role="button">Sign Up Now!</a>  
            </div>
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
