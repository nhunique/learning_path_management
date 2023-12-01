<?php
session_start();
include 'includes/class-autoload.inc.php';
isset($_POST['pathID']) ? $_POST['pathID'] :"";
isset($_SESSION['cloneID'] ) ? $_SESSION['cloneID']  : "";
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if( isset($_POST['clone'])) {

        $originalPathID = $_POST['pathID'];
        $userID = $_SESSION['userID'];

        $learningPath = new LearningPath();
        $learningPath->setClonePath( $userID,$originalPathID);
        
        $cloneID= $_SESSION['cloneID'] ;
        echo $_SESSION['cloneID'] ;

        header("Location: path_details.php?clone=$cloneID");
        exit();
    }
}


