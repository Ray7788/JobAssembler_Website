<!DOCTYPE html>
<html>
    <head>
        <title>Registration</title>
        <style type="text/css">
            .container{
                justify-content:center;
                display: flex;
                align-items:center;
                height: 100vh;
                font-size:40px;
                position:relative;
            }
            .inputBox{
                float:right;
                font-size:40px;
            }
            label{
                float:left;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <form action="https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/register.php" method="POST">
                    <h3>Job Assembler</h3>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="inputBox">
                    <br><br>
                    <label for="forename">Forename:</label>
                    <input type="text" name="forename" id="forename" class="inputBox">
                    <br><br>
                    <label for="surname">Surname:</label>
                    <input type="text" name="surname" id="surname" class="inputBox">
                    <br><br>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="inputBox">
                    <br><br>
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" class="inputBox">
                    <br><br>
                    <input type="submit" value="Submit" class="button" name="submitButton">

                    <?php
                        ini_set('error_reporting', E_ALL);
                        ini_set('display_errors', 1);

                        if(isset($_REQUEST['submitButton'])){
                            $password = $_POST['password'];
                            $confirmPassword = $_POST['confirmPassword'];
                            $username = $_POST['username'];
                            $forename = $_POST['forename'];
                            $surname = $_POST['surname'];
                            if($password != $confirmPassword){
                                $warningMsg = "Please ensure both passwords are the same.";
                            }
                            #Username must be 6-30 alphanumeric characters
                            if (!preg_match('/^[A-Za-z\d\-]{6,30}$/', $username)) {
                                $warningMsg = "Invalid username given. Must be 6-30 alphanumeric characters.";
                            }
                            #Password must be greater than 8 characters long
                            if (strlen($password) < 8) {
                                $warningMsg = "Invalid password given. Must be at least 8 characters long.";
                            }
                            #Password must be fewer than 1024 characters long
                            if (strlen($password) > 1024) {
                                $warningMsg = "Invalid password given. Must not be more than 1024 characters long.";
                            }
                            #Forename and surname must be at least one character long
                            if (strlen($forename) < 1 || strlen($surname) < 1) {
                                $warningMsg = "Invalid name given. Both a forename and surname must be given.";
                            }
                            #Forenames and surnames must be fewer than 255 bytes long
                            if (strlen($forename) > 255){
                                $warningMsg = "Invalid forename given. Forename not be more than 63 characters long.";
                            }
                            if (strlen($surname) > 255){
                                $warningMsg = "Invalid surname given. Surname not be more than 63 characters long.";
                            }
                            #Username must not already be taken.
                            if (User::check_username_exists($connection, $username)) {
                                $warningMsg = "Invalid username given. Username is already in use, please choose another.";
                            }
                            try {
                                $result = User::create_user($connection, $username, $password, $forename, $surname);
                                if (!$result) {
                                    $warningMsg = "There was an error with the database. Please try again later.";
                                }
                            } catch (Exception $exception) {
                                $warningMsg = "There was an error with the database. {$exception->getMessage()} Please try again later.";
                            }

                            echo("<br>" . $warningMsg);
                        }
                    ?>
            </form>


        </div>
    </body>
</html>