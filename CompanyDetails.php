<!DOCTYPE html>
<html lang="en">
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Company Details - JobAssembler</title>

         <!-- Bootstrap CSS 5.1.3-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <style type="text/css">
            body {
                display: flex;
                justify-content: center;
                background-image: linear-gradient(to left, #5B86E5,#6DD5FA);
            }
            .container-fluid{
                background-color: #fff;
                width: 80vw;
                height: 110h;
                position: relative;
                display: flex;
                border-radius: 20px;
                justify-content: center;
                align-items: center;
                top: 50px;
                font-size:40px;  
            }
            .b{
                width: 75vw;
                height: 100vh;
                overflow: hidden;
                font-family:"Helvetica";

            }
            .display-1{
            font: 900 24px '';
            font-size:1.5em;
            font-family:"Helvetica";
            margin: 5px 0;
            text-align: center;
            letter-spacing: 1px;
            }
            .inputBox{
            width: 100%;
            margin-bottom: 20px;
            outline: none;
            border: 0;
            padding: 5px;
            border-bottom: 2px solid rgb(60,60,70);
            font: 900 16px '';
            font-size:40px;
            }
           
            label{
                float:left;
            }

            #validationMsg{
                text-align:center;
                color:red;
                font-size:30px;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            function ValidateForm(name, description, warning){
                warning.innerHTML = ""; //Set it to blank first in case the user got the validation wrong first time round.
                if(/^[0-9a-z\s]+$/i.test(name.value) == false || name.value.length > 64 || name.value.length < 3){
    	            warning.innerHTML = "Company name must be between 3 and 64 alphanumeric characters.";
                }else if(description.value.length < 20){
                    warning.innerHTML = "Please ensure you've added an appropriate description (over 20 characters long).";
                }
                if(warning.innerHTML != ""){
                    return false;
                }else{
                    return true;
                }
            }

            $(document).ready(function(){
                $("#companyForm").submit(function (e){  
                    let warning = document.getElementById("validationMsg");
                    e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                    let form = document.getElementById('companyForm');
                    var validForm = ValidateForm(form.elements[0], form.elements[1], warning);
                    form.elements[2].value = localStorage.getItem("userID");
                    if(validForm){
                        $.ajax({
                            type:"POST",
                            url:"api/companyRegister.php",
                            data: $(this).serialize(),
                            success: function(data){
                                localStorage.removeItem("userID");
                                window.location = "login.php";  //Where to go if successful (Needs changing to the main screen)
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
        <div class="container-fluid">
        <div class="b">

            <h1 class="display-1" align="center">Company Details</h1>

            <form id="companyForm" name="companyForm">
                <label for="name">Company Name:</label>
                <br>
                <input type="text" name="name" id="name" class="inputBox">
                <br>
                <label for="description">Description:</label>
                <br>
                <textarea id="description" rows="3" cols="50" name="description" class="inputBox"></textarea>
                <br>
                <input type="hidden" name="userID" id="userID">
                
                <input type="submit" class="btn btn-primary btn-lg"  value="Submit">

                <a href="login.php">Or login to join a pre-existing company</a>
 
                <p id="validationMsg"></p>

                              
            </form>
            </div>
        </div>
    </body>
</html>