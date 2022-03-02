<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/Normalise.css"> <!-- Include a custom CSS for the website later-->

    <title>JobAssembler</title>

    <style>
         /* Please Don't edit this part. For scroll Styling */
        main {   
            scroll-snap-type: y mandatory;
            overflow: scroll;
            height: 100vh;
        }

        /*For Logo border*/
        .d-inline-block-align-top {border-radius: 5px;}

        .container-fluid {
            scroll-snap-align: start;
            width: 100vw;
            height: 100vh;
        }

        body {
            overflow: hidden;
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
            <a class="btn btn-primary" href="login_page.php">Login</a>
        </div>
        </a>
    </nav>
   
    <main>
    <div class="container-fluid" style="padding:0%; margin: 0;">
        <div class="jumbotron text-center mt-5 mx-2" style="background-color: #fdf5df; height: 600px;">

            <p></p>
            <h1 class="display-4" style="color: #5ebec4;">Struggling with finding Employment or Employees?</h1>
            <p></p>
            <h1 class="display-6" style="color: #5ebec4;">Ever wish it was as easy as Tinder?</h1>
            <hr class="my-3">
            <p class="lead" style="color: #5ebec4;">JobAssembler Is!</p>
        
            <a class="btn btn-primary btn-lg" href="SignUpPage2.php" role="button" style="background-color: #f92c85;">Sign Up Now!</a>
            <p></p> 
        </div> 
    </div>

    <div class="container-fluid"  style="padding:0%; margin: 0;">
        <div class="jumbotron text-center mt-5 mx-2" style="background-color:cyan; height: 600px;">
            <h1 class="display-6" style="vertical-align:-webkit-baseline-middle"> Direct Contact Between Employers and Employees</h1>
            <p></p>
            <hr class="my-4">
            <p class="lead" style="color: #5ebec4;"></p>
            <a class="btn btn-primary btn-lg" href="SignUpPage2.php" role="button">Sign Up Now!</a>
        </div>
    </div>
    
    <div class="container-fluid" style="padding:0%; margin: 0;">
        <div class="jumbotron text-center mt-5 mx-2" style="background-color: #f7f7f7; height: 600px;">
            <h1 class="display-4" style="color: #7da2a9;">The Best of the Best</h1>
            <p></p>
            <a class="btn btn-primary btn-lg" href="SignUpPage2.php" role="button">Sign Up Now!</a>
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
