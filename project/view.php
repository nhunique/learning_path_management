<?php 
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";

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

   
    
   <section class="container">
        <div class="container" id="view">
        <h1 class="container-heading">View Your Learning Paths</h1>
        <?php     
                
            $email       = $_SESSION['email'];
            LearningPathContr::viewLearningPath($email); 

        ?>
        </div>
    </section>
    


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</html>