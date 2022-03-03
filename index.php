<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/index-Normalise-custom.css"> <!-- Include a custom CSS for the website later-->

    <title>JobAssembler</title>

    <style>
        * {
        box-sizing: border-box;
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

        /* For Logo border*/
        .d-inline-block-align-top {border-radius: 5px;}

        /* For navbar */
        .container-fluid {
            scroll-snap-align: start;
            width: 100vw;
            height: 100vh;
        }

        /* delete extra scroll bars */
        body {
            overflow: hidden;
        }

        /* For every page */
        .container-fluid {
            padding:0%; margin: 0%;
        }

        /* 1st page */
        .page1 {
      
            width: 120vw;
            height: 170vh;
            background: linear-gradient(
            135deg,
            hsl(170deg, 80%, 70%),
            hsl(190deg, 80%, 70%),
            hsl(250deg, 80%, 70%),
            hsl(320deg, 80%, 70%));
            background-size: 200% 200%;
            animation: gradient-move 10s ease alternate infinite;
        }
         /*3rd page */
        .page3 {
            width: 100vw;
            height: 150vh;
            background: linear-gradient(
                135deg,
            rgb(225, 238, 210),
            rgb(219, 208, 167),
            rgb(230, 155, 3),
            hsl(320deg, 80%, 70%));
            background-size: 200% 200%;
            animation: gradient-move 10s ease alternate infinite;
        }
        /* 2nd page */
        .page2 {
            width: 100vw;
            height: 150vh;
            background: linear-gradient(
                135deg,
            rgb(226, 241, 141),
            rgb(194, 255, 159),
            rgb(113, 252, 136),
            rgb(103, 190, 240));
            background-size: 200% 200%;
            animation: gradient-move 10s ease alternate infinite;
        }

        @keyframes gradient-move {
        0% {
          background-position: 0% 0%;
        }
        100% {
          background-position: 100% 100%;
        }
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
            <a class="btn btn-primary" href="login_page.php">Log in</a>
        </div>
        </a>
    </nav>
   
    <main>
    <!-- page1 -->
    <div class="container-fluid">
        <div class="jumbotron text-center mt-5 mx-2" style="height: 600px;">
            <div class="page1">
            <h1 class="display-4" style="color: #5ebec4;">Struggling with finding Employment or Employees?</h1>
            <h1 class="display-6" style="color: #5ebec4;">Ever wish it was as easy as Tinder?</h1>
            <hr class="my-3">
            <p class="lead" style="color: #5ebec4;">JobAssembler Is!</p>
            <a class="btn btn-primary btn-lg" href="SignUpPage2.php" role="button" style="background-color: #f92c85;">Sign Up Now!</a>
        </div>
        </div> 
    </div>

    <!-- page2 -->
    <div class="container-fluid">
        <div class="jumbotron text-center mt-5 mx-2" style="height: 600px;">
            <div class="page2">
            <h1 class="display-4" style="vertical-align:-webkit-baseline-middle; color: #FFFFCC"> Direct Contact Between Employers and Employees  :)</h1>
            <h1 class="display-6" style="color: #FFFFCC;">Customized for campus students!</h1>
            <hr class="my-4">
            <p class="lead" style="color: #5ebec4;"></p>
            <a class="btn btn-primary btn-lg" href="SignUpPage2.php" role="button">Sign Up Now!</a>
            </div>
        </div>
    </div>

    <!-- page3 -->
    <div class="container-fluid">
        <div class="jumbotron text-center mt-5 mx-2" style="height: 600px;">
            <div class="page3">
            <h1 class="display-4" style="color: #7da2a9;">The Best of the Best</h1>
            <a class="btn btn-primary btn-lg" href="SignUpPage2.php" role="button">Sign Up Now!</a>
            </div>
        </div>
    </div>

    </main>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
</main>
    </body>
</html>
