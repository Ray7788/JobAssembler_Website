<!DOCTYPE html>

<html>
<head>
 <title>Employee Form</title>
    <style>
        .container{
                justify-content:center;
                display: flex;
                align-items:center;
                font-size:30px;
                position:relative;
                padding:20px;
        }
        .inputBox{
                float:right;
                margin:10px;
        }
        input {
                padding:12px 20px;
                font-size:20px;
                width:600px;
        }
        textarea {
                padding:12px 20px;
                font-size:15px;
                width:600px;
                height:100px;
        }
        label {
                float:left;
                margin:10px;        
        }
        .button{
                width:200px;
                height:50px;
                font-size: 35px;
                float:center;
        }
        select{
                width:100px;
                height:30px;
                font-size:25px;
                float:center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
                $("employeeForm").submit(function(event){
                        event.preventDefault();
                        $.ajax({
                                type:"POST",
                                url:"api/EmployeeRegister.php",
                                data:$(this).serialize(),
                                success: function(data){
                                        window.location = "login.php"
                                        alert("success");
                                },
                                error: function(){
                                        alert("Fail");
                                }
                        })
                })
        })
    </script>
</head>
<body>
    <form id="employeeForm" name="employeeForm">
    <div class="container">
        <h2>Employee Sign Up Page</h>
        <hr>
        <label for="email">Email: </label>
        <input type="text" name="email" placeholder="Enter Email" id="email" class="inputBox" required>
        <br><br>
        <label for="biography">Biography: </label>
        <textarea type="text" name="biography" placeholder="Enter any relevent information about youself"id="biography"class="inputBox" required></textarea>
        <br><br>
        <label for="profilePic">Profile Pic: </label>
        <input type="file" id="profilePic" name="profilePic" accept="image/*">
        <br><br>
        <label for="CV">CV: </label>
        <input type="file" id="CV" name="CV">
        <p>By Creating an account you agree to our <a href="privacypolicy.html">Terms and Conditions</a><p>
        <button type="button" onclick="window.location.href='SignUpPage2.php';" class="button">Cancel</button>
        <input type="submit" value="Sign Up" class="button">
        </form>
</body>

</html>
