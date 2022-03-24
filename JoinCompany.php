<?php
require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
$user = $_SESSION["user"];


if(!$user->is_authenticated()){
    header("Location: index.php");
    die(0);
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
        <title>Join Company</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    </head>
    
    <body>
        <form action="JoinCompany.php" method="GET" name="searchForm">
            <table>
                <tr>
                    <td><input type="text" name="txt" value="<?php echo isset($_GET['txt']) ? $_GET['txt'] : ''; ?>" placeholder="Enter your company name" /></td>
                    <td><input type="submit" name="" value="Search" /></td>
                </tr>
            </table>
        </form>   
        <?php
            //Show the user the keywords they previously entered.
            //If the txt variable is empty then nothing has been searched for.
            if(empty($txt)){
                echo("Please search for your company using the search bar above.");
            }else{
                if($noOfCompanies > 0){
                    echo("Your search returned: <b>" . $noOfCompanies . " </b>results. <br>");
                    echo("You searched for: <b>'" . $wordsForDisplay . "'</b><br><hr><br>");

                    for($x=0;$x<$noOfCompanies;$x++){
                        echo('
                        <h3>'.$companies[$x][1].'</h3>
                        '.$companies[$x][2].'<br>
                        <button class="btn btn-outline-primary" id="join'.$companies[$x][0].'" onclick="joinPressed()">Send Join Request</button>
                        <br><br>'); 
                    }
                }else{
                    echo("You searched for: <b>'" . $wordsForDisplay . "'</b><br>");
                    echo('There were no results for your search. Please ensure you have typed the right company name.');
                }
            }

            
        ?>
    </body>

</html>