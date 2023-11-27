<?php
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";

    include 'includes/class-autoload.inc.php';
    include 'includes/navbar.inc.php';
    include 'includes/pathsManager.inc.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $pathID       = $_POST['pathID'];
        $email = $_SESSION['email'];
        $learningPath = new LearningPath();
        $learningPath->updateSpecificLearningPath($pathID, $email);

        header("Location: pathsManager.php?error=none");
    }

?>
