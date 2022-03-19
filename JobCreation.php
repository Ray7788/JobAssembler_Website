<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Creation - JobAssembler</title>

   <!-- CSS Styling-->
    <link rel="stylesheet" href="CSS/JobCreation.css">
    <link rel="stylesheet" href=../JobAssembler/CSS/JobCreation.css>
    <!-- Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
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

<style>
    body {
        display: flex;
        justify-content: center;
        background-image: linear-gradient(to left, #e1eec3, #f05053);
    }
    .container-fluid{
        background-color: #fff;
        width: 550px;
        height: 750px;
        position: relative;
        display: flex;
        border-radius: 10px;
        justify-content: center;
        align-items: center;
        top: 50px;
    }
    .b{
        width: 400px;
        height: 650px;
        overflow: hidden;
    }
    .header1{
        font: 900 24px '';
        margin: 5px 0;
        text-align: center;
        letter-spacing: 3px;
    }
    .e{
        width: 100%;
        margin-bottom: 20px;
        outline: none;
        border: 0;
        padding: 10px;
        border-bottom: 2px solid rgb(60,60,70);
        font: 900 16px '';
    }

</style>
</head>
<body>
    <div class="container-fluid">
    <div class="b">


    <form name="jobCreateForm" id="jobCreateForm">
        <p>
        <h1 class="header1">Enter Job Detail</h1>
        <br><br>

            <h3 class="d">Job Title<br></h3>
            <input type="text" name="title" class="e" id="title"  placeholder="Input Title" required>
            <br><br>
            <h3 class="d">Job Description</h3>
            <textarea type="textbox" name="description" class="e" id="description" rows="3" cols="50" placeholder="More Details" required></textarea>
            <br><br>
            <h3 class="d">Job Location</h3> 
            <input type="text" name="location" class="e" id="location"  placeholder="Post Code" required>
            <br><br>
         
            <!-- button -->
            <input type="submit" class="f" value="Submit">
            
        </p>
       
        
    </form>
    
    </div>
    </div>

    <!-- <p class="mt-5 mb-3 text-muted">&copy; X17 2021-2022</p> -->
</body>
</html>