<?php 
    session_start();
    isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
    include 'includes/class-autoload.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Home</title>
</head>
<body>
    <?php include 'includes/navbar.inc.php'?>

        <?php
        if($_SESSION['email']){
            $view = new UserView($_SESSION['email']);
            $view->helloName($_SESSION['email']);
        } else {
            echo "You need to login.";
            header("Location: login.php");
        }
        ?>
    
    <div class="container">All Learning Paths 
        <?php
        $allPaths= new LearningPath();
        $allPaths->displayLearningPaths();

        ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>