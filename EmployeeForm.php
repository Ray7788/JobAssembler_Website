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
    <script>

    </script>
</head>
<body>
    <form id="employeeForm" name="employeeForm" onsubmit="return formValidation()">
    <div class="container">
        <h2>Employee Sign Up Page</h>
        <hr>
        <label for="email">Email: </label>
        <input type="text" name="email" placeholder="Enter Email" id="email" class="inputBox" required>
        <br><br>
        <label for="biography">Biography: </label>
        <textarea type="text" name="biography" placeholder="Enter any relevent information about youself"id="biography"class="inputBox" required></textarea>
        <br><br>
        <label for="credentials">Credentials: </label>
        <textarea type="text" name="credentials" placeholder="Enter any of your credentials such as degrees, programming languages known, etc" id="credentials" class="inputBox" required></textarea>
        <br><br>
        <select>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
        <option value="Prefer not to say">Prefer not to say</option>
        </select>
        <br><br>
        <label for="birth">Birth Date:</label>
        <input type="date" id="birth">
        <br><br><br>
        <p>By Creating an account you agree to our <a href="Link to our policy page">Terms and Conditions</a><p>
        <button type="button" onclick="window.location.href='Put link to previous page here';" class="button">Cancel</button>
        <input type="submit" value="Sign Up" class="button">
        </form>
</body>

</html>
