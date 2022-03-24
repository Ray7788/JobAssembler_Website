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
$companies = $statement->fetchAll();
$noOfCompaies = sizeof($companies);

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
                    <td><input type="text" name="k" value="<?php echo isset($_GET['txt']) ? $_GET['txt'] : ''; ?>" placeholder="Enter your search keywords" /></td>
                    <td><input type="submit" name="" value="Search" /></td>
                </tr>
            </table>
        </form>   
        <?php
            //Show the user the keywords they previously entered.
            echo("Your search returned: <b>" . $noOfCompanies . " <b>results. <br>");
            echo("You searched for: <b>'" . $wordsForDisplay . "'<b>");
        ?>
    </body>

</html>