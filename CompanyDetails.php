<!DOCTYPE html>
<html>
    <head>
        <title>Company Details</title>
        <style type="text/css">
            .container{
                justify-content: center;
                display:flex;
                align-items: center;
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
                    if(validForm){
                        $.ajax({
                            type:"POST",
                            url:"api/companyRegister.php",
                            data: $(this).serialize(),
                            success: function(data){
                                window.location = "login.php"  //Where to go if successful (Needs changing to the main screen)
                                alert("Success");
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
            <form id="companyForm" name="companyForm">
                <h3>Company Details</h3>
                <label for="name">Company Name:</label>
                <input type="text" name="name" id="name" class="inputBox">
                <br><br>
                <label for="description">Description:</label><br>
                <textarea id="description" rows="4" cols="50" name="description" class="inputBox"></textarea>
                <br><br>
                <input type="submit">
                <br><br>
                <p id="validationMsg"></p>
                <a href="JoinCompany.php">Or join a pre-existing company</a>
            </form>
        </div>
    </body>
</html>