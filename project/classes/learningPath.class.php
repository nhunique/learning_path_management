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
    
        $sql = "SELECT DISTINCT title, description, userID, pathID  FROM learning_paths 
                WHERE pathID=?";
        $stmt = $this->connect()->prepare($sql);
    
    
        // Execute the statement
        if (!$stmt->execute([$pathID])) {
            // Close the statement
            $stmt = null;
            header("Location: update.php?error=stmtfailed");
            exit();
        }
    
        // Fetch all rows
        $row = $stmt->fetchAll();
        return $row;
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
        $sql = "SELECT title, description, userID, pathID  FROM learning_paths 
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
    public function updateSpecificLearningPath($pathID, $email, $updateTitle, $updateDescription, $updateUrlTitles, $updateUrlLinks) {
        // Check if the userID exists in the users table
        $userIDExists = $this->checkUserExists($email);
    
        if (!$userIDExists) {
            header("Location: pathsManager.php?error=invaliduser");
            exit();
        }
    
        $connection= $this->connect();

        // Update learning_paths (parent) first
        $sql = "UPDATE learning_paths SET title=?, description=?, userID=? WHERE pathID=?";
        $stmt = $connection->prepare($sql);

        // Check for prepare error
        if (!$stmt) {
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }

        // Execute the update
        $result = $stmt->execute([$updateTitle, $updateDescription, $userIDExists, $pathID]);

        // Check for execute error
        if (!$result) {
            
            header("Location: ../project/pathsManager.php?error=updatefailed");
            exit();
        }

       

        // Update existing urls 
        $sqlUrls = "UPDATE urls SET urlTitle=?, urlLink=? WHERE urlID=?";
        $stmtUrls = $connection->prepare($sqlUrls);

        // Check for prepare error
        if (!$stmtUrls) {
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }

        $selectSql = "SELECT * FROM urls WHERE pathID = ?";
        $stmtSelect = $connection->prepare($selectSql);
        // Check for prepare error
        if (!$stmtSelect) {
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }
        $stmtSelect->execute([$pathID]);
        $urls = $stmtSelect->fetchAll();

        
        $numOfUpdate = count($updateUrlTitles);
        $numOfOrigin = count($urls);
        $count = 0;

        if ($count < $numOfOrigin && $count <$numOfUpdate){
            foreach ($urls as $i =>  $url) {
                //update existing urls
                $urlID = $url['urlID'];
                $updateUrlTitle = $updateUrlTitles[$i];
                $updateUrlLink = $updateUrlLinks[$i];
        
                // Execute the update
                $resultUrls = $stmtUrls->execute([$updateUrlTitle, $updateUrlLink, $urlID]);
        
                // Check for execute error
                if (!$resultUrls) {
                    echo $stmtUrls->errorInfo(); // or log the error
                    header("Location: ../project/pathsManager.php?error=urlupdatefailed");
                    exit();
                }
                $count++;
            }
         

        }  elseif ($count >= $numOfOrigin && $count <= $numOfUpdate)  {
            for( $j = $count ; $j <= $numOfUpdate; $j++){

                // This URL is beyond the existing ones, so add it as a new URL
                $newUrlTitle = $updateUrlTitles[$j];
                $newUrlLink = $updateUrlLinks[$j];
        
                // Insert the new URL into the database
                $insertSql = "INSERT INTO urls (pathID, urlTitle, urlLink) VALUES (?, ?, ?)";
                $stmtInsert = $this->connect()->prepare($insertSql);
        
                if (!$stmtInsert) {
                    header("Location: ../project/pathsManager.php?error=urlinsertfailed");
                    exit();
                }
        
                // Execute the insert
                $resultInsert = $stmtInsert->execute([$pathID, $newUrlTitle, $newUrlLink]);
        
                // Check for execute error
                if (!$resultInsert) {
                    header("Location: ../project/pathsManager.php?error=urlinsertfailed");
                    exit();
                }
               
            }
        }

        //debug
        $testsql = "SELECT * from learning_paths JOIN urls ON learning_paths.pathID = urls.pathID WHERE learning_paths.pathID =?";
        $stmtTest = $connection->prepare($testsql);
        $stmtTest->execute([$pathID]);
        $test = $stmtTest->fetchAll();
        
        $stmtTest =null;
        // Close the statements
        $stmtInsert = null;        
        $stmtSelect = null;
        $stmt = null;
        $stmtUrls = null;


        print 'count:'. $count. '<br>'; print 'orgin:'.$numOfOrigin.'<br>'; print'update:'. $numOfUpdate . '<br>';
         //Redirect if everything was successful
        //header("Location: pathsManager.php?error=none");
       // header("Location: view.php");
        //exit();
        return $test;
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