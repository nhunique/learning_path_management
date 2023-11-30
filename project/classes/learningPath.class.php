<?php

class LearningPath extends Dbh{

  
    // Function to check if a user with a vote for a path exists
    private function  checkUserExistsInUserVote($userID) {
        $sql = "SELECT userID FROM user_vote 
                WHERE userID = ?";
        $stmt = $this->connect()->prepare($sql);

        // Check if stmt execute
        if (!$stmt->execute([$userID])) {
            // Close stmt
            $stmt = null;
            header("Location: ../pathsManager.php?error=stmtfailed");
            exit();
        }

        $userID = $stmt->fetchAll();

        // Check if a user was found
        if (!$userID) {
            return -1; // No user found
        }

        return $userID['userID'];
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
            return null; // No user found
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

  
    

    public function getVote($pathID) {
        $connection = $this->connect();
    
        // Execute the SELECT query
        $query = "SELECT votes FROM learning_paths WHERE pathID = :pathID";
        $stmt = $connection->prepare($query);
    
        if (!$stmt->execute([':pathID' => $pathID])) {
            header("Location: ../project/pathsManager.php?error=stmtfailed");
            exit();
        }
    
        // Fetch the result
        $result = $stmt->fetch();
    
        // Check if the result is not empty
        if ($result !== false) {
            // Return the actual value of votes
            return $result['votes'];
        } else {
            //when no result is found
            return 0; 
        }
    }
    
    

    //Handle Voting System

    public function hasUserVoted($pathID, $userID) {
        $query = "SELECT * FROM user_vote WHERE pathID = :pathID AND userID = :userID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":pathID", $pathID, PDO::PARAM_INT);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? true : false;
    }
    
    protected function downvoteLearningPath($pathID, $userID) {
        $this->removeVote($pathID, $userID);
    }
    
    protected function upvoteLearningPath($pathID, $userID) {
        // Check if the user has downvoted before
        if ($this->hasDownvoted($pathID, $userID)) {
            // If yes, remove the downvote first
            $this->removeVote($pathID, $userID);
        }
    
        // Perform upvote
        $query = "INSERT INTO user_vote (pathID, userID) VALUES (:pathID, :userID)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":pathID", $pathID, PDO::PARAM_INT);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
    
        // Update the votes in learning_paths
        $this->updateVotes($pathID, 1);
    }
    
    protected function removeVote($pathID, $userID) {
        $query = "DELETE FROM user_vote WHERE pathID = :pathID AND userID = :userID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":pathID", $pathID, PDO::PARAM_INT);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
    
        // Update the votes in learning_paths
        $this->updateVotes($pathID, -1);
    }
    
    protected function hasDownvoted($pathID, $userID) {
        $query = "SELECT * FROM user_vote WHERE pathID = :pathID AND userID = :userID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":pathID", $pathID, PDO::PARAM_INT);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? true : false;
    }
    
    private function updateVotes($pathID, $increment) {
        $query = "UPDATE learning_paths SET votes = votes + :increment WHERE pathID = :pathID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":pathID", $pathID, PDO::PARAM_INT);
        $stmt->bindParam(":increment", $increment, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function handleVote($pathID, $voteType) {
        
    
        $pathID = $_POST['pathID'];
        $userID = $_SESSION['userID'];
    
        // Check if the user has voted before
        $hasVoted = $this->hasUserVoted($pathID, $userID);
    
        if ($voteType === 'upvote') {
            if (!$hasVoted) {
                // Perform upvote SQL
                $this->upvoteLearningPath($pathID, $userID);
                header("Location: index.php?vote=up");
                exit();
            } else {
                // Alert or handle already voted case
                header("Location: index.php?error=already_voted");
                exit();
            }
        } elseif ($voteType === 'downvote') {
            if ($hasVoted) {
                // Perform downvote SQL
                $this->downvoteLearningPath($pathID, $userID);
                header("Location: index.php?vote=down");
                exit();
            } else {
                // Alert or handle not voted yet case
                header("Location: index.php?error=not_voted_yet");
                exit();
            }
        }
    }
    
    //Handle Cloning Path

    public function clonePath(){



    }


    public function displayLearningPaths(){

        //query
        $query = "SELECT learning_paths.pathID, learning_paths.title, learning_paths.description, learning_paths.votes, users.firstName, users.lastName
        FROM learning_paths
        JOIN users ON learning_paths.userID = users.userID";
 
        $result = $this->connect()->query($query);
 
        if ($result) {
            while ($row = $result->fetch()) {
                $pathID = $row['pathID'];
                $title = $row['title'];
                $votes= $row['votes'];
                $description = $row['description'];
                $userFirstName = $row['firstName'];
                $userLastName = $row['lastName'];
 
 
                // Display learning path information
                echo '<div class="card p-5">';
                echo '<h3 class="card-header">' . htmlspecialchars($title) . '</h3>';
                echo '<div class="card-body">';
                echo '<h4 class="card-title">' . htmlspecialchars($description) . '</h4>';
                echo '<p class="card-text">User: ' . htmlspecialchars($userFirstName). " ". htmlspecialchars($userLastName) . '</p>';
                // Display associated URLs
                $urls = $this->getUrls($pathID); 
 
                echo '<ul class="list-group list-group-flush">';
                foreach ($urls as $url) {
                    echo '<li class="list-group-item"><a href="' . htmlspecialchars($url['urlLink']) . '">' . htmlspecialchars($url['urlTitle']) . '</a></li>';
                  }
                echo '</ul>';
                echo '</div>';
                echo '<div class="container-fluid">';
                //Display votes system
                
                // Upvote and Downvote buttons
                echo '<div class="row">';
                echo '<div class="col-sm-3 col-md-6 col-lg-4 d-flex">';

                echo '<form action="vote-handler.php" method="post">';
                echo '<input type="hidden" name="pathID" value="' . $pathID . '">';
                echo '<button type="submit" class="btn btn-success mt-3" name="upvote">Upvote</button>';
                echo '</form>';
 
                echo '<form action="vote-handler.php" method="post">';
                echo '<input type="hidden" name="pathID" value="' . $pathID . '">';
                echo '<button type="submit" class="btn btn-danger mt-3" name="downvote">Downvote</button>';
                echo '</form>';
                echo '</div>';

                echo '<div class="col-sm-3 col-md-2 col-lg-5">';
                echo '<button type="text" class="btn bg-secondary mt-3 text-white" value="Votes: ' . $votes . '">Votes: ' . $votes . '</button>';
                echo '</div>';

                echo '<div class="col-sm-3 col-md-4 col-lg-3 d-flex">';
                echo '<form action="clone-handler.php" method="post">';
                echo '<input type="hidden" name="pathID" value="' . $pathID . '">';
                echo '<button type="submit" class="btn btn-info text-white mt-3" name="clone">CLONE</button>';
                echo '</form>';
               
                echo '<form action="clone-handler.php" method="post">';
                echo '<input type="hidden" name="pathID" value="' . $pathID . '">';
                echo '<button type="submit" class="btn btn-light text-info mt-3" name="share">Share</button>';
                echo '</form>';
                echo '</div>';

                echo '</div>';
                echo '</div>';
                echo '</div>'; 
                
                
                // Close the card
            }
        } else {
        // Handle query execution error
        echo "Error: " . $this->connect()->errorInfo()[2];
    }
 
        // Close the result set
        $result= null;
 
    }


    
}