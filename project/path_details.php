<?php
session_start();
include 'includes/class-autoload.inc.php';


 if($_SERVER['REQUEST_METHOD'] === 'GET'){

    $learningPath = new LearningPath();

    if (isset($_GET['clone'])) {
      

        //Display clone Path information
        $learningPath->displayClonePathInfo($_SESSION['cloneID']);

    } else if ( isset($_GET['identifier'])) {
        // Handle shared path retrieval
        $sharedIdentifier = $_GET['identifier'];

        $sharedPathID = $learningPath->getPathByUniqueIdentifier($sharedIdentifier);

        $current_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
       
        $current_url_with_identifier = $current_url . '?' . $sharedIdentifier;
        
        // echo $current_url_with_identifier;
        
        // var_dump( $sharedPathID);
        // echo $sharedPathID[0]['pathID'];
        if ($sharedPathID) {
             //Display the shared path information
           $learningPath->viewSpecificPath($sharedPathID[0]['pathID']);
           echo '<p class="text-info"> Copy this link and share to your friends: <a href="'.$current_url_with_identifier.'">'. $current_url_with_identifier .'</a></p>';
        } else {
            // Handle invalid or expired shared path
            echo "Invalid or Expired Shared Path";
        }
    }
}