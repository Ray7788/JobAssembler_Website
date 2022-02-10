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
            function ValidateForm(){
                //JAMES can do this bit
                return true;
            }

            $(document).ready(function(){
                $("#signUpForm").submit(function (e){  
                    e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                    //Clear Error messages gets rid of any alerts after where it is put.
                    //CURRENTLY THINKING THE ERROR IS TO DO WITH TH URL
                    var validForm = ValidateForm();
                    if(validForm){
                        $.ajax({
                            type:"POST",
                            url:"https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/register.php",
                            data: $(this).serialize(),
                            success: function(data){
                                window.location = "https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/login_page.php"  //Where to go if successful
                                alert("Success");
                            },
                            error: function(xhr){
                                alert($(this).serialize);
                                var obj = xhr.responseJSON;
                                alert("An error occured: " + xhr.status + " " + xhr.statusText);
                                if(Object.keys(obj).includes("message")){
                                    alert(obj["message"]);
                                }else{
                                    alert("An unknown error has occurred. Please try again later.");
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

                    <?php
                        ini_set('error_reporting', E_ALL);
                        ini_set('display_errors', 1);
                        
                        
                    ?>
            </form>


        </div>
    </body>
</html>