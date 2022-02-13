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
            function ValidateForm(companyName, description){
                //Change message in the sign up screen
                let warning = document.getElementById("validationMsg");
                warning.innerHTML = ""; //Set it to blank first in case the user got the validation wrong first time round.
                if(/^[0-9a-z]+$/i.test(companyName.value) == false || companyName.length > 30 || companyName.length < 6){
    	            warning.innerHTML = "Username must be 6-30 alphanumeric characters.";
                }else if(description.length < 20){
                    warning.innerHTML = "Please ensure you've added an appropriate description.";
                }
                if(warning.innerHTML != ""){
                    return false;
                }else{
                    return true;
                }
            }

            $(document).ready(function(){
                $("#companyForm").submit(function (e){  
                    ClearErrorMessages();
                    e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                    let form = document.getElementById('companyForm');
                    var validForm = ValidateForm(form.elements[0], form.elements[1]);
                    if(validForm){
                        $.ajax({
                            type:"POST",
                            url:"https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/companyRegister.php",
                            data: $(this).serialize(),
                            success: function(data){
                                window.location = "https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/login_page.php"  //Where to go if successful
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
            <form id="companyForm" name="companyForm">
                <h3>Company Details</h3>
                <label for="companyName">Company Name:</label>
                <input type="text" name="companyName" id="companyName" class="inputBox">
                <br><br>
                <label for="description">Description:</label><br>
                <textarea id="description" rows="4" cols="50" name="description" class="inputBox"></textarea>
                <br><br>
                <input type="submit">
                <br><br>
                <p id="validationMsg"></p>
            </form>
        </div>
    </body>
</html>