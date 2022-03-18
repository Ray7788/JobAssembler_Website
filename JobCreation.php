<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        // Shamelessly stole this directly from James's code
        $(document).ready(function(){
            $("#jobCreateForm").submit(function (e){
                e.preventDefault();     //Stops the normal HTML form behaviour of changing files
                $.ajax({
                    type:"POST",
                    //url:"https://web.cs.manchester.ac.uk/v31903mb/JobAssembler/api/jobCreate.php",
                    url:"api/jobCreate.php",
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
    <title>Job Creation  JobAssembler</title>

    <!-- CSS Styling-->
    <link rel="stylesheet" href="CSS/JobCreation.css">
    <link rel="stylesheet" href=../JobAssembler/CSS/JobCreation.css>
</head>
<body>
    <div class="content">
        <div class="login-box">
            <h1>Enter Job Detail</h1>
            
    <form name="jobCreateForm" id="jobCreateForm">
        <!-- <p> -->
            <div class="output-frame">
            <label for="username">Job title</label>
            <input type="text" name="title" id="title" required><br><br>
            </div>

            <div class="output-frame">
            Job description<br>
            <textarea type="textbox" name="description" id="description" rows="4" cols="50" required></textarea><br><br>
            </div>

            <div class="output-frame">
            Job Location<br>
            <input type="text" name="location" id="location" required><br><br>
            </div>
            
            <input type="submit" value="Submit">
        <!-- </p> -->
    </form>
        </div>
    </div>
</body>
</html>