<!DOCTYPE html>
<html>
    <head>
        <title>Sign-Up Page</title>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            function ValidateForm(username, forename, surname, password, confirmPassword, warning){
                warning.innerHTML = ""; //Set it to blank first in case the user got the validation wrong first time round.
                if(/^[0-9a-z]+$/i.test(username.value) == false || username.value.length > 30 || username.value.length < 6){
    	            warning.innerHTML = "Username must be 6-30 alphanumeric characters.";
                }else if(password.value != confirmPassword.value){
                    warning.innerHTML = "Make sure you've typed the same password twice.";
                }else if(password.value.length < 8 || password.value.length > 1024){
                    warning.innerHTML = "Password must be between 8 and 1024 characters long.";
                }else if(forename.value.length < 1 || surname.value.length < 1){
                    warning.innerHTML = "Invalid name given. Both forename and surname must be given.";
                }else if(forename.value.length > 63){
                    warning.innerHTML = "Invalid forename given. Make sure it's less than 64 characters long.";
                }else if(surname.value.length > 63){
                    warning.innerHTML = "Invalid surname given. Make sure it's less than 64 characters long.";
                }
                //This just makes it so you don't have to put 'return false' in every if statement.
                if(warning.innerHTML != ""){
                    return false;
                }else{
                    return true;
                }
            }

            $(document).ready(function(){
                $("#signUpForm").submit(function (e){  
                    //Change message in the sign up screen
                    let warning = document.getElementById("validationMsg");
                    e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                    let form = document.getElementById('signUpForm');
                    var validForm = ValidateForm(form.elements[0], form.elements[1], form.elements[2], form.elements[3], form.elements[4], warning);
                    if(validForm){
                        $.ajax({
                            type:"POST",
                            //url:"https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/register.php",
                            url:"api/register.php",
                            data: $(this).serialize(),
                            success: function(data){
                                window.location = "login_page.php"  //Where to go if successful
                            },
                            error: function(xhr){
                                //alert($(this).serialize);
                                var obj = xhr.responseJSON;
                                //alert("An error occured: " + xhr.status + " " + xhr.statusText);
                                if(Object.keys(obj).includes("message")){
                                    warning.innerHTML = obj["message"];
                                }else{
                                    warning.innerHTML = "An unknown error has occurred. Please try again later.";
                                }
                            }

                        })
                    }
                })
            })

        </script>
    </head>

    <body>
        <div class="container">
            <form id="signUpForm" name="signUpForm">
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
                    <input type="submit">
                    <br><br>
                    <p id="validationMsg"></p>

                    <?php
                        ini_set('error_reporting', E_ALL);
                        ini_set('display_errors', 1);
                        
                        
                    ?>
            </form>


        </div>
    </body>
</html>