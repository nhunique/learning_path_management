<?php

//grabbing user input
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email       = $_SESSION['email'];
    $title          = $_POST['title'];
    $description    = $_POST['description'];
    $urlTitles    = $_POST['urlTitles'];
    $urlLinks    = $_POST['urlLinks'];

    //instanciate learningPathContr class
    $learningPath =  new LearningPathContr($title, $description, $email , $urlTitles, $urlLinks);
            

    if(isset($_POST['create'])){
   
        //Running error handler and create learning Path
        $learningPath->createLearningPath();

        header("Location: pathsManager.php?error=none");

    }

    
}

