
<?php
session_start();
include 'includes/class-autoload.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' ){
   if (isset($_POST['share']) ){

        $originalPathID = $_POST['pathID'];
        $userID = $_SESSION['userID'];

        $learningPath = new LearningPath();

        // Generate a unique identifier for the shared path
        $uniqueIdentifier = $learningPath->generateUniqueIdentifier($originalPathID, $userID);
        //var_dump($uniqueIdentifier);
        // Store the unique identifier in the database 
        $learningPath->storeIdentifier($originalPathID, $uniqueIdentifier);
    

        // Redirect the user to the shared path
        header("Location: path_details.php?identifier=$uniqueIdentifier");
        exit();
    }  else {
        // Handle errors or redirect back to the original path if something goes wrong
        //header("Location: index.php?error=sharefailed");
        exit();
    }
}
?>