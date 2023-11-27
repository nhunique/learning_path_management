<?php 
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";
    isset($_POST['update']) ? $_POST['update'] : " ";
  
  
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

   <h1>Delete</h1>
   <section class="container">
        <div class="container" id="view">
        <h1 class="container-heading">View</h1>
        <?php     
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $pathID       = $_POST['pathID'];
                $email        = $_SESSION['email'];
               
            }
            ?>

            <form id="deleteForm" method="post" action="delete-process.php">
                <fieldset>
                    <div class="container">
                    <legend>Delete Learning Path</legend>
                        <?php
                            $email       = $_SESSION['email'];
                            LearningPathContr::viewSpecificPath($pathID);
                        ?>
                        <input type="hidden" name="pathID" value="<?=$pathID?>">;
                        <button type="submit" class="btn btn-primary mt-3" name="delete" value="delete">Delete</button>
                    </div>

                    
                <fieldset>
            </form>
        </div>
    </section>





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
</script>
</html>