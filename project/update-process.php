<?php
session_start(); 

$_SESSION['email'] = isset($_SESSION['email']) ? $_SESSION['email'] : "";

include 'includes/class-autoload.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {  

        $pathID = isset($_POST['pathID']) ? (int)$_POST['pathID'] :"" ;
        $email = $_SESSION['email'];
        $updateTitle =isset($_POST['updateTitle']) ? $_POST['updateTitle'] :" " ;
        $updateDescription = isset($_POST['updateDescription']) ? $_POST['updateDescription'] :" " ;
        $updateUrlTitles = isset($_POST['updateUrlTitles']) ? $_POST['updateUrlTitles'] :" " ;
        $updateUrlLinks = isset($_POST['updateUrlLinks'] )? $_POST['updateUrlLinks'] :" " ;


        //print_r($_POST);

        $learningPath = new LearningPath();   
        $path = $learningPath->updateSpecificLearningPath($pathID, $email, $updateTitle, $updateDescription, $updateUrlTitles, $updateUrlLinks);

       // print_r($path);
        header("Location: pathsManager.php?error=none");
        exit(); 
    }
}
?>
