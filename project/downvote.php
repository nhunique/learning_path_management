<?php include 'includes/class-autoload.inc.php'?>

<?php
session_start(); 
isset($_SESSION['email']) ? $_SESSION['email'] : " ";
isset($_POST['downvote']) ? $_POST['downvote'] : " ";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['downvote'])){
        // Include your class or any necessary initialization

        $pathID = $_POST['pathID'];
        $email = $_SESSION['email']; 

        $learningPath = new LearningPath(); 
        $learningPath->upvoteLearningPath($pathID, $email);

        // Redirect back to the main page or wherever you want the user to go
        header("Location: index.php?error=none");
        exit();
    }
}