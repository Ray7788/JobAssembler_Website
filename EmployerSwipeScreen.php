<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /index.php");
    die(0);
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if (!$user->is_authenticated()) {
    header("Location: /index.php");
    die(0);
}
$applicants = array();
$pdo = Database::connect();

$query = "SELECT * FROM JobPostings WHERE CompanyID = :companyID";
$statement = $pdo->prepare($query);
$statement->execute(["companyID" => $user->company_id]);
$jobs = $statement->fetchAll();
$num=1;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employer Swipe Screen</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        
        <style type="text/css">
            .container{
                justify-content:center;
                display: flex;
                align-items:center;
                height: 12em;
                font-size:2.5em;
                position:relative;
            }
            .box{
                background: bisque;
                border-radius: 5px;
                box-shadow: 3px 10px 15px #abc;
                
            }
            .buttonHolder{
                justify-content:center;
                display:flex;
                align-items:center;
                height:5em;
                font-size:40px;
                position:relative;
            }
            .button {
                margin-left:auto;
                margin-right:auto;
                border: none;
                color: black;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                background-color:aqua;
            }
        </style>
        
        <script>
            var userID = <?php echo($userID); ?>;
            var currentJob = 0;
            $(function(){
                $(".dropdown-menu a").click(function(){
                    alert($(this).text());
                    currentJob = $(this).text();
                });
            });

            function writeToCard(){
                
            }
        </script>
    </head>
    <body>
        <?php
            echo("You are signed in as: " . $user->username);
            echo(implode("",$jobs[0]));
            
        ?>
        
        <div class="container">
            <div class="box">
                <p id="card" name="card">
                    
                </p>
                

            </div>  
        </div>
        <div class="buttonHolder">

            <!--<button class="button" id="noButton" onclick="buttonPressed(0)">NO</button>
            <button class="button" id="yesButton" onclick="buttonPressed(1)">YES</button>-->

        </div>
        <div class="buttonHolder">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Dropdown button
                </button>
                <div class="dropdown-menu">
                    <?php
                        for($x=0; $x<count($jobs);$x++){
                            echo('<a class="dropdown-item">' . $jobs[$x][1]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        //writeToCard();
    </script>
</html>