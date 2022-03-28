<?php

require_once(__DIR__ . "/classes/database.php");
require_once(__DIR__ . "/classes/user.php");
session_start();
$user = $_SESSION["user"];
$companyID = $user->company_id;
$userID = $user->user_id;

if(!$user->is_authenticated()){
    header("Location: /index.php");
    die(0);
}

//Get the search entry
$txt = isset($_GET['txt']) ? $_GET['txt'] : '';
$usersApplied = array();
$pdo = Database::connect();
$wordsForDisplay = "";
if(empty($txt)){
    //If the user hasn't searched yet, just display all the entries.
    //Get list of users who are in the JoinRequest table for the company the present user is a part of (and where companyAccepted is 0).
    $query = "SELECT UserAccounts.UserID, UserAccounts.Username, UserAccounts.Forename, UserAccounts.Surname 
    FROM (UserAccounts INNER JOIN CompanyJoinRequests ON UserAccounts.UserID = CompanyJoinRequests.UserID)
    WHERE CompanyJoinRequests.CompanyID = :companyid AND CompanyJoinRequests.CompanyAccepted = 0";
    $statement = $pdo->prepare($query);
    $statement->execute([
        "companyid" => $companyID
    ]);
    $usersApplied = $statement->fetchAll(PDO::FETCH_NUM); 
}else{
    $query = "SELECT UserAccounts.UserID, UserAccounts.Username, UserAccounts.Forename, UserAccounts.Surname
    FROM (UserAccounts INNER JOIN CompanyJoinRequests ON UserAccounts.UserID = CompanyJoinRequests.UserID)
    WHERE (CompanyJoinRequests.CompanyID = :companyid AND CompanyJoinRequests.CompanyAccepted = 0) AND (";
    //Format each of the search words
    $keywords = explode(' ', $txt);
    foreach($keywords as $kw){
        //Check if the search keyword is similar to Username, Forename or Surname
        $query .= "UserAccounts.Username LIKE '%" . $kw . "%' OR ";
        $query .= "UserAccounts.Forename LIKE '%" . $kw . "%' OR ";
        $query .= "UserAccounts.Surname LIKE '%" . $kw . "%' OR ";
        $wordsForDisplay .= $kw.' ';
    }
    //Get rid of the last part and close the bracket from the start of the query
    $query = substr($query, 0, strlen($query)-4);
    $query .= ")";
    $wordsForDisplay = substr($wordsForDisplay, 0, strlen($wordsForDisplay) -1);
    $statement = $pdo->prepare($query);
    $statement->execute([
        "companyid" => $companyID
    ]);
    $usersApplied = $statement->fetchAll(PDO::FETCH_NUM);
}
$noOfUsers = count($usersApplied);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Users To Your Company - JobAssembler</title>
        <meta name="viewport" content="width=device-width, intitial-scale=1">
        <!--Bootstrap CSS-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            //var userID = <?php echo($userID); ?>;
            var companyID = <?php echo($companyID); ?>;

            function acceptPressed(userID){
                //userID here is the user that I am currently accepting.
                dataArray = {"companyID":companyID, "userID":userID};
                $.ajax({
                    type:"POST",
                    url:"api/companyAccepting.php",
                    data: dataArray,
                    success:function(data){
                        document.getElementById("errorMsg").innerHTML = "User successfully added to your company. Go and tell them they can now go through and accept or decline job applicants.";
                    },
                    error: function(xhr){
                        var obj = xhr.responseJSON;
                        if(Object.keys(obj).includes("message")){
                            document.getElementById("errorMsg").innerHTML = obj["message"];
                        }else{
                            document.getElementById("errorMsg").innerHTML = "An unknown error has occurred. Please try again later.";
                        }
                    }
                })
            }
        </script>
    </head>
    <body>
        <form action="CompanyAddUsers.php" method="GET" name="searchForm">
            <table>
                <tr>
                    <td><input type="text" name="txt" value="<?php echo isset($_GET['txt']) ? $_GET['txt'] : ''; ?>" placeholder="Enter account Username, Forename or Surname" /></td>
                    <td><input type="submit" name="" value="Search" /></td>
                </tr>
            </table>
        </form>   
        <b><i><p id="errorMsg"></p></i></b>
        <?php
            //Show the user the keywords they have entered
            //If the txt variable is empty then everything is displayed.
            echo("Please search for the account you're trying to add by their Username, Forename or Surname.");
            if($noOfUsers > 0){
                echo("The search returned: <b>" . $noOfUsers . " </b>results. <br>");
                if(!empty($txt)){
                    echo("You searched for: <b>'" . $wordsForDisplay . "'</b>");
                }
                echo("<br><hr>");                
                for($x=0;$x<$noOfUsers;$x++){
                    echo('
                    <h3>'.$usersApplied[$x][1].'</h3>
                    '.$usersApplied[$x][2].' '.$usersApplied[$x][3].'<br>
                    <button class="btn btn-outline-primary id="accept'.$usersApplied[$x][0].'" onclick="acceptPressed('.$usersApplied[$x][0].')">Accept User Into Company</button>
                    <hr>');
                }
            }else{
                if(empty($txt)){
                    echo("<br>There are no users who have applied to your company that you haven't already dealt with.");
                }else{
                    echo("<br>There were no results for your search. Please ensure you've entered the correct information<br>");
                    echo("You searched for: <b>'" . $wordsForDisplay . "'</b>");
                }
            }
        ?>

    </body>
</html>