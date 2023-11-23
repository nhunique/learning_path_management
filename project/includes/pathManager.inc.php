

<?php

//grabbing user input
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['create'])){

        $userID         = $_POST['userID'];
        $title          = $_POST['title'];
        $description    = $_POST['description'];
        $urls    = $_POST['urls'];

        //instanciate learningPathContr class
        $learningPath =  new LearningPathContr($title, $description, $userID , $urls);
        
        //Running error handler and user login
        $learningPath->createLearningPath();


        //going back to front page
        header("Location: ../project/pathManager.php?error=none");
        $learningPath->checkErrorCode($_GET['error']);
    

    }
}