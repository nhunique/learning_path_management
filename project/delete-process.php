<?php
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";

    include 'includes/class-autoload.inc.php';
    include 'includes/navbar.inc.php';


    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $pathID       = $_POST['pathID'];
        $email = $_SESSION['email'];
        $learningPath = new LearningPath();
        $learningPath->deleteSpecificLearningPath1($pathID);

        header("Location: view.php?error=none");
    }

?>
