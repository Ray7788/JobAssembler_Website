<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
if (!isset($_SESSION["user"]) || !($_SESSION["user"] instanceof User)) {
    header("Location: index.php");
    die(0);
}
$user = $_SESSION["user"];
$userID = $user->user_id;
if(!$user->is_authenticated()){
    header("Location: index.php");
    die(0);
}
$user->get_user();
if ($user->company_id != 0) {
    header("Location: main.php");
}

//Get the search entry
$txt = isset($_GET['txt']) ? $_GET['txt'] : '';
$query = "SELECT * FROM Companies WHERE ";
$wordsForDisplay = "";
//Format each of the search keywords
$keywords = explode(' ', $txt);     //Separates the text into an array
foreach($keywords as $kw){
    $query .= "Name LIKE '%" . $kw . "%' OR ";
    $wordsForDisplay .=  $kw.' ';
}
//Get rid of the final OR bit
$query = substr($query, 0, strlen($query)-4);
$wordsForDisplay = substr($wordsForDisplay, 0, strlen($wordsForDisplay)-1);

$companies = array();
$pdo = Database::connect();
$statement = $pdo->prepare($query);
$statement->execute();
$companies = $statement->fetchAll(PDO::FETCH_NUM);

$noOfCompanies = count($companies);


?>
<!DOCTYPE html>
<html>
    <head>
        <title>Join Company  - JobAssembler</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS 4.6.1 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <style>
        body {
        display: flex;
        background-image: linear-gradient(to left, #e1eec3, #f05053);
        }

        .container-fluid{
                
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 5%;
        }

        .b{
            background-color: #fff;
            font-family:"Helvetica";
            padding: 7%;
            border-radius: 80px;

        }

        </style>
        <script>
            var userID = <?php echo($userID); ?>;
            var companyArray = <?php echo json_encode($companies) ?>;

            function joinPressed(companyID){
                dataArray = {"companyID":companyID, "userID":userID};
                $.ajax({
                    type:"POST",
                    url:"api/companyJoining.php",
                    data:dataArray,
                    success:function(data){
                        document.getElementById("errorMsg").innerHTML = "Job request successfully sent. Please wait for your company to accept.";
                    },
                    error: function(xhr){
                        var obj = xhr.responseJSON;
                        if(Object.keys(obj).includes("message")){
                            document.getElementById("errorMsg").innerHTML = obj["message"];
                        }else{
                            document.getElementById("errorMsg").innerHTML = "An unknown error has occurred. Please try again later.";
                        }
                    }
                });

            }
        </script>

    </head>

    
    <body>
    <div class="container-fluid">
    <div class="b">
        
        <h1 style="text-align:center"> Join A Company </h1>
        <br>
        <form action="JoinCompany.php" method="GET" name="searchForm" class="form-inline" style="align-items: center;">
        <div class="form-group mb-2">
        
        <table>
                <tr>
                    <td><input type="text" name="txt" class="form-control" value="<?php echo isset($_GET['txt']) ? $_GET['txt'] : ''; ?>" placeholder="Enter your company name" /></td>
                    <td><input type="submit" name="" value="Search" class="btn btn-primary mb-2" /></td>
                </tr>
        </table>
         
        </form>  
        </div> 
         
        <b><i><p id="errorMsg"></p></i></b>


        <?php
            //Show the user the keywords they previously entered.
            //If the txt variable is empty then nothing has been searched for.
            if(empty($txt)){
                echo("Please search for your company using the search bar above.");
            }else{
                if($noOfCompanies > 0){
                    echo("Your search returned: <b>" . $noOfCompanies . " </b>results. <br>");
                    echo("You searched for: <b>'" . $wordsForDisplay . "'</b><br><hr>");

                    for($x=0;$x<$noOfCompanies;$x++){
                        echo('
                        <h3>'.$companies[$x][1].'</h3>
                        '.$companies[$x][2].'<br>
                        <button class="btn btn-outline-primary" id="join'.$companies[$x][0].'" onclick="joinPressed('.$companies[$x][0].')">Send Join Request</button>
                        <hr>'); 
                    }
                }else{
                    echo("You searched for: <b>'" . $wordsForDisplay . "'</b><br>");
                    echo('There were no results for your search. Please ensure you have typed the right company name.');
                }
            }

            
        ?>
    </body>

</html>