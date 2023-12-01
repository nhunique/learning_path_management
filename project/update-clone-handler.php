<?php
session_start(); 
include 'includes/class-autoload.inc.php';
//include 'includes/navbar.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $learningPath = new LearningPath();   
    if (isset($_POST['update'])) {
        // Logic for updating the clone path
        $cloneID = $_POST['cloneID'];

        $userID = $_SESSION['userID'];
        $updateTitle =isset($_POST['updateTitle']) ? $_POST['updateTitle'] :" " ;
        $updateDescription = isset($_POST['updateDescription']) ? $_POST['updateDescription'] :" " ;
        $updateUrlTitles[] = isset($_POST['updateUrlTitles']) ? $_POST['updateUrlTitles'] :" " ;
        $updateUrlLinks[] = isset($_POST['updateUrlLinks'] )? $_POST['updateUrlLinks'] :" " ;


        // Implement your update logic here

        $path = $learningPath->updateCloneLearningPath($cloneID, $userID, $updateTitle, $updateDescription, $updateUrlTitles, $updateUrlLinks);


        echo "Updating clone path with ID: $cloneID";
        header("Location: index.php?update=success");
    } elseif (isset($_POST['delete'])) {
        // Logic for deleting the clone path and associated URLs
        $cloneID = $_POST['cloneID'];
        
        // Implement your delete logic 
       
        $Clonepath = $learningPath->deleteSpecificClonePath1($cloneID);
        
       
        // Display a confirmation or success message
        echo "Deleting clone path with ID: $cloneID";
        header("Location: index.php?delete=success");
    } else {
        // Invalid action
        echo "Invalid action.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}
?>

