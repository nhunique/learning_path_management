<?php

class LearningPath extends Dbh{

    protected function checkLearningPath($email){

        $sql = "SELECT userID FROM learning_paths WHERE userID=? ";
        $stmt = $this->connect()->prepare($sql);

        //Check if stmt execute
        if(!$stmt->execute([$email])){
            //close stmt
            $stmt = null;
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }

        //check if user not exits in database
        $result = false;
        if($stmt->rowCount() > 0){
            $result = true;
        }
        else {
            $result = false;
        }

        return $result;
    }


    //set learningpath's info

    protected function setLearningPath( $title, $desciption, $userID, $urls){
        
        $sql = "INSERT INTO learning_paths ( title, description, userID) 
        VALUES(?,?,?)";
        $stmt =$this->connect()->prepare($sql);

        // Get the last inserted pathID
        $pathID = $this->connect()->lastInsertId();

        // Insert into the urls table for each URL
        $sqlUrls = "INSERT INTO urls (pathID, url) VALUES (?, ?)";
        $stmtUrls = $this->connect()->prepare($sqlUrls);

        foreach ($urls as $url) {
            $stmtUrls->execute([$pathID, $url]);
        }

        if(!$stmt->execute([ $title, $desciption, $userID]) || !$stmtUrls->execute([$pathID, $url])){
            $stmt = null;
            $stmtUrls =null;
            header("Location: ../project/pathManager.php?role=create&error=stmtfailed");
            exit();
        }
        $stmt = null;
       

    }

    //get a learningPath for a user
    protected function getLearningPath($userid){

        //grab path info from database
        $sql = "SELECT title, description, userID FROM learning_paths WHERE userID=? ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$userid]);

        $rows = $stmt->fetchALL(); 

        //Check if stmt execute
        if(!$stmt->execute([$userid])){
            //close stmt
            $stmt = null;
            header("Location: ../project/pathManager.php?error=stmtfailed");
            exit();
        }

        //check if path not exits in database
        if($stmt->rowCount() == 0){

            $stmt = null;
            header("Location: ../project/pathManager.php?error=pathnotfound");
            exit();
        }

        $stmt=null;

    }
}