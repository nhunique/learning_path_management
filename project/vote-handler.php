<?php include 'includes/class-autoload.inc.php'?>


<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include your class or any necessary initialization
    $learningPath = new LearningPath();

    if (isset($_POST['upvote'])) {
        $learningPath->handleVote($learningPath, 'upvote');
    } elseif (isset($_POST['downvote'])) {
        $learningPath->handleVote($learningPath, 'downvote');
    }
}


?>