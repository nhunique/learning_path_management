<?php

class LearningPath extends Dbh{

    protected function checkLearningPathExists($pathID){

        $sql = "SELECT pathID FROM learning_paths WHERE pathID=? ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$pathID]);
        //Check if stmt execute
        if(!$stmt->execute([$pathID])){
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

   // Function to check if a user with the given email exists
    private function checkUserExists($email) {
        $sql = "SELECT DISTINCT userID FROM users WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);

        // Check if stmt execute
        if (!$stmt->execute([$email])) {
            // Close stmt
            $stmt = null;
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }

        $userID = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user was found
        if (!$userID) {
            return false; // No user found
        }

        return $userID['userID'];
    }


    //INSERT LEARNING PATH
    protected function setLearningPath($title, $description, $email, $urlTitles, $urlLinks) {
         // Check if the userID exists in the users table
        $userIDExists = $this->checkUserExists($email);

        if (!$userIDExists) {
            header("Location: ../project/pathsManager.php?error=invaliduser");
            exit();
        }

        $connection = $this->connect();
        $sql = "INSERT INTO learning_paths (title, description, userID) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
    
        if (!$stmt->execute([$title, $description, $userIDExists])) {
            $stmt = null;
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }
    
        // Get the last inserted pathID
        $pathID = $connection->lastInsertId();
    
        // Insert into the urls table for each URL
        $sqlUrls = "INSERT INTO urls (pathID, urlTitle, urlLink) VALUES (?, ?, ?)";
        $stmtUrls = $connection->prepare($sqlUrls);

        foreach ($urlTitles as $index => $urlTitle) {
            // Check if the index exists in $urlLinks array
            $urlLink = isset($urlLinks[$index]) ? $urlLinks[$index] : null;

            $urlLink = $urlLinks[$index];
            if (!$stmtUrls->execute([$pathID, $urlTitle, $urlLink])) {
                // Handle the error
                $stmt = null;
                $stmtUrls = null;
                header("Location: ../project/pathsManager.php?error=stmtfailed");
                exit();
            }
        }
        
        $stmt = null;
        $stmtUrls = null;
    }
    

    public function getSpecificPath($pathID){
        // grab path and associated urls info from the database
    
        $sql = "SELECT DISTINCT title, description, userID, learning_paths.pathID  FROM learning_paths 
                JOIN urls ON learning_paths.pathID = urls.pathID
                WHERE learning_paths.pathID=?";
        $stmt = $this->connect()->prepare($sql);
    
    
        // Execute the statement
        if (!$stmt->execute([$pathID])) {
            // Close the statement
            $stmt = null;
            header("Location: update.php?error=stmtfailed");
            exit();
        }
    
        // Fetch all rows
        $rows = $stmt->fetchAll();
        return $rows;
    }

    
    //GET LEARNING PATH
    protected function getLearningPath($email) {
         // Check if the userID exists in the users table
         $userIDExists = $this->checkUserExists($email);

         if (!$userIDExists) {
             header("Location: ../project/pathsManager.php?error=invaliduser");
             exit();
         }

        // grab path and associated urls info from the database
        $sql = "SELECT title, description, userID, learning_paths.pathID , GROUP_CONCAT(urlLink) AS urlLinks FROM learning_paths 
                JOIN urls ON learning_paths.pathID = urls.pathID
                WHERE userID=?";
        $stmt = $this->connect()->prepare($sql);
    
        // Execute the statement
        if (!$stmt->execute([$userIDExists])) {
            // Close the statement
            $stmt = null;
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }
    
        // Fetch all rows
        $rows = $stmt->fetchAll();
    
        // Check if no paths exist in the database
        if (empty($rows)) {
            $stmt = null;
            header("Location: ../project/pathsManager.php?error=pathnotfound");
            exit();
        }
    
        $stmt = null;
        return $rows;
    }
    

    //GET URLS fetch URLs for a specific pathID
    public function getUrls($pathID){
        $sql = "SELECT urlID, urlTitle, urlLink FROM urls WHERE pathID = ?";
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute([$pathID])) {
            
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }

        // Fetch all rows
        $urls = $stmt->fetchAll();

        return $urls;
    } 

    
    //UPDATE LEARNING PATH
    public function updateSpecificLearningPath($pathID, $email) {
        // Check if the userID exists in the users table
        $userIDExists = $this->checkUserExists($email);
    
        if (!$userIDExists) {
            header("Location: pathsManager.php?error=invaliduser");
            exit();
        }
    
        $paths = $this->getLearningPath($email); //returns rows
        foreach ($paths as $path) {
            $pathID = $path['pathID'];
            $pathTitle = $path['title'];
            $pathDescription= $path['description'];
        }
    
        $sql = "UPDATE learning_paths SET title=?, description=?, userID=? WHERE pathID=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$pathTitle, $pathDescription, $userIDExists, $pathID]);
    
        // Update urls

        
        $sqlUrls = "UPDATE urls SET urlTitle=?, urlLink=? WHERE pathID=?";
        $stmtUrls = $this->connect()->prepare($sqlUrls);
    
        $urls = $this->getUrls($pathID); //returns rows
        foreach ($urls as $url) {
            $urlID = $url['urlID'];
            $urlTitle = $url['urlTitle'];
            $urlLink= $url['urlLink'];
            $stmtUrls->execute([$urlTitle, $urlLink, $pathID]);
        }

        // Check for errors
        if (!$stmt || !$stmtUrls) {
            $stmt = null;
            $stmtUrls = null;
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }
    
        $stmt = null;
        $stmtUrls = null;
    }
    

    
    //delete learningpath's info

    public function deleteSpecificLearningPath( $pathID){
        
        // Delete child record url
        $sqlUrls = "DELETE FROM urls WHERE pathID = ?";
        $stmtUrls = $this->connect()->prepare($sqlUrls);

        //Delete learningpath record
        $sql = "DELETE FROM learning_paths WHERE pathID =?";
        $stmt =$this->connect()->prepare($sql);



        if(!$stmt->execute([ $pathID]) || !$stmtUrls->execute([$pathID])){
            $stmt = null;
            $stmtUrls =null;
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
       

    }

    public function deleteSpecificLearningPath1($pathID) {
        // Delete child record urls
        $connection = $this->connect();
        $sqlUrls = "DELETE FROM urls WHERE pathID = ?";
        $stmtUrls = $connection->prepare($sqlUrls);
    
        // Delete learning path record
        $sql = "DELETE FROM learning_paths WHERE pathID = ?";
        $stmt = $connection->prepare($sql);
    
        try {
            // Begin a transaction
            $connection->beginTransaction();
    
            // Delete URLs
            if (!$stmtUrls->execute([$pathID])) {
                throw new Exception("Error deleting URLs");
            }
    
            // Delete learning path
            if (!$stmt->execute([$pathID])) {
                throw new Exception("Error deleting learning path");
            }
    
            // Commit the transaction if everything is successful
            $connection->commit();
    
            // Redirect or handle success
            header("Location: pathsManager.php?success=delete_success");
            exit();
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $connection->rollBack();
    
            // Handle the error appropriately
            header("Location: pathsManager.php?error=delete_failed");
            exit();
        } finally {
            // Close statements
            $stmt = null;
            $stmtUrls = null;
        }
    }
    
}