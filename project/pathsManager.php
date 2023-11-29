<?php 
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";

    $error = isset($_GET['error']) ? $_GET['error'] :null;
    checkErrorCode($error);

    
    
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Learning Paths Management</title>
</head>
<body>
    <?php include 'includes/class-autoload.inc.php'?>
    <?php include 'includes/navbar.inc.php'?>
    <?php include 'includes/pathsManager.inc.php'?>

    <!-- Path Manager System  -->
    <div class="container mt-5 mb-5">
        <h1 class="display-2">Path Manager System </h1>
    </div>

    <!-- Create Path Form-->
    <section id="pathManager" class="container">
        <div class="container" id="create">
            <form id="CreateForm" method="post" action="pathsManager.php">
                <fieldset>
                <legend class="text-primary display-4">Create New Learning Path</legend>
                    
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter your title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="5" cols="100" placeholder="Enter your description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="url">URL:</label>
                        <div id="urlFields">
                            <div class="input-group mb-2">
                            <input type="text" class="form-control" name="urlTitles[]" placeholder="Enter URL Title" required>
                            <input type="url" class="form-control" name="urlLinks[]" placeholder="Enter URL" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="removeUrlField(this)">Remove</button>
                            </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary" type="button" onclick="addUrlField()">Add URL</button>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" name="create" value="create">Create</button> 
                <fieldset>
            </form>         
        </div>
    </section>
    
    <hr>

    <div class="container mt-3 ml-3"><a class="btn btn-primary mt-3"  href="view.php">View Your Learning Paths</a></div>

    


    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        function addUrlField() {
            const urlFields = document.getElementById('urlFields');
            const newUrlField = document.createElement('div');
            newUrlField.className = 'input-group mb-2';
            newUrlField.innerHTML = `
            <input type="text" class="form-control" name="urlTitles[]" placeholder="Enter URL Title" required>
            <input type="url" class="form-control" name="urlLinks[]" placeholder="Enter URL" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="removeUrlField(this)">Remove</button>
            </div>
            `;
            urlFields.appendChild(newUrlField);
        }

        function removeUrlField(button) {
            const urlField = button.closest('.input-group');
            urlField.remove();
        }

        // JavaScript to handle switching between View and Edit modes
        document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("form[action='pathsManager.php'] button[name='view']").addEventListener("click", function (event) {
            // Add the "updateMode" field to the form
            var form = document.querySelector("form[action='pathsManager.php']");
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "updateMode";
            input.value = "true";
            form.appendChild(input);

            // Submit the form
            form.submit();
            });
        });


    </script>
</html>


<?php

    //Check error code
    function checkErrorCode($errorCode){

        // Check if the "error" parameter is present in the URL
        if (isset($_GET['error'])) {
            $errorCode = $_GET['error'];

            // Display something based on the error code
            switch ($errorCode) {
                case 'none':
                    echo '<script type="text/javascript">alert("Create/Update successfully.");</script>';
                    break;
                case 'invaliduser':
                    echo "Invalid email error!";
                   
                    break;
                case 'emptyinput':
                    echo "Empty input error!";
              
                    break;
                
                default:
                    echo "Unknown error!";
                    break;
            }
        }

    }
?>

