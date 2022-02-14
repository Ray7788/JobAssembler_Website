<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Log in</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        // Shamelessly stole this directly from James's code
        $(document).ready(function(){
            $("#loginForm").submit(function (e){
                let warning = document.getElementById("warningMessage");
                e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                let form = document.getElementById('loginForm');
                $.ajax({
                    type:"POST",
                    url:"https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/login.php",
                    data: $(this).serialize(),
                    success: function(data){
                        window.location = "main.php"  //Where to go if successful
                    },
                    error: function(xhr){
                        var obj = xhr.responseJSON;
                        if(Object.keys(obj).includes("message")){
                            warning.innerHTML = obj["message"];
                        }else{
                            warning.innerHTML = "An unknown error has occurred. Please try again later.";
                        }
                    }
                })
            });})
    </script>
</head>
<body>
	<h1>Log in</h1>
	<p>Please enter your username and password: </p>
	<form name="loginForm" id="loginForm">
        <p>
            Username<br>
            <input type="text" name="username" id="username" required><br><br>
            Password<br>
            <input type="password" name="password" id="password" required><br><br>
            <input type="submit" value="Submit">
            <p name="warningMessage" id="warningMessage"></p>
        </p>
	</form>
</body>
</html>
