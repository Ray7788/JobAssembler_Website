<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    <title>Job Creation</title>
</head>
<body>
    <h1>Enter job detail</h1>
    <form name="jobCreateForm" id="jobCreateForm">
        <p>
            Job title<br>
            <input type="text" name="title" id="title" required><br><br>
            Job description<br>
            <textarea type="textbox" name="description" id="description" rows="4" cols="50" required></textarea><br><br>
            Job Location<br>
            <input type="text" name="location" id="location" required><br><br>
            <input type="submit" value="Submit">
        </p>
    </form>
</body>
</html>