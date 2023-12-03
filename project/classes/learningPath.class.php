<?php

class LearningPath extends Dbh{

    // Function to check if a user with a vote for a path exists
    private function  checkPathIDExist($pathID) {
        $sql = "SELECT pathID FROM learning_paths 
                WHERE pathID = ?";
        $stmt = $this->connect()->prepare($sql);

        // Check if stmt execute
        if (!$stmt->execute([$pathID])) {
            // Close stmt
            $stmt = null;
            header("Location: index.php?error=stmtfailed");
            exit();
        }

        $pathID = $stmt->fetchAll();

        // Check if a user was found
        if (!$pathID) {
            return []; // No user found
        }

        return $pathID['pathID'];
    }
    
    
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
            header("Location: pathsManager.php?error=stmtfailed");
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
            header("Location: pathsManager.php?error=invaliduser");
            exit();
        }

        $connection = $this->connect();
        $sql = "INSERT INTO learning_paths (title, description, userID) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
    
        if (!$stmt->execute([$title, $description, $userIDExists])) {
            $stmt = null;
            header("Location: pathsManager.php?error=stmtfailed");
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
                header("Location: pathsManager.php?error=stmtfailed");
                exit();
            }
        }
        
        $stmt = null;
        $stmtUrls = null;
    }
    

    public function getSpecificClonePath($cloneID){
        // grab path info from the database
    
        $sql = "SELECT  title, description, userID, cloneID  FROM clone_paths 
                WHERE pathID=?";
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute([$cloneID])) {
            // Handle the error
            $stmt = null;
            header("Location: path_details.php?error=stmtfailed");
            exit();
        }
    
         // Fetch all rows
        $rows = $stmt->fetchAll();

        // Check if any rows were fetched
        if (empty($rows)) {
            // Handle the case where no rows are found
            header("Location: path_details.php?error=stmtfailed");
            exit();
        } 

        // Return row
        return $rows;
    }

    public function getSpecificPath($pathID){
        // grab path info from the database
    
        $sql = "SELECT  title, description, userID, pathID  FROM learning_paths 
                WHERE pathID=?";
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute([$pathID])) {
            // Handle the error
            $stmt = null;
            header("Location: index.php?error=stmtfailed");
            exit();
        }
    
         // Fetch all rows
        $rows = $stmt->fetchAll();

        // Check if any rows were fetched
        if (empty($rows)) {
            // Handle the case where no rows are found
            header("Location: index.php?error=stmtfailed");
            exit();
        } 

        // Return row
        return $rows;
    }

    
    //GET LEARNING PATH
    protected function getLearningPath($email) {
         // Check if the userID exists in the users table
         $userIDExists = $this->checkUserExists($email);

         if (!$userIDExists) {
             header("Location: pathsManager.php?error=invaliduser");
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
            header("Location: pathsManager.php?error=stmtfailed");
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

            header("Location: index.php?error=stmtfailed");
           exit();
        }

        // Fetch all rows
        $urls = $stmt->fetchAll();
    
        return $urls;
    } 
    
    //GET URLS fetch URLs for a specific cloneID
    public function getUrlsByCloneID($clonePathID){
        $sql = "SELECT urlID, urlTitle, urlLink FROM urls WHERE cloneID = ?";
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute([$clonePathID])) {

            header("Location: index.php?error=stmtfailed");
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
            header("Location: view.php?error=invaliduser");
            exit();
        }
    
        $connection= $this->connect();

        // Update learning_paths (parent) first
        $sql = "UPDATE learning_paths SET title=?, description=?, userID=? WHERE pathID=?";
        $stmt = $connection->prepare($sql);

        // Check for prepare error
        if (!$stmt) {
            header("Location: view.php?error=stmtfailed");
            exit();
        }

        // Execute the update
        $result = $stmt->execute([$updateTitle, $updateDescription, $userIDExists, $pathID]);

        // Check for execute error
        if (!$result) {
            
            header("Location: view.php?error=update_failed");
            exit();
        }
    }
            
    //UPDATE ClONE LEARNING PATH
    public function updateCloneLearningPath($cloneID, $userID, $updateTitle, $updateDescription, $updateUrlTitles, $updateUrlLinks){
    

        $connection= $this->connect();

        // Update learning_paths (parent) first
        $sql = "UPDATE clone_paths SET cloneTitle=?, cloneDescription=?, userID=? WHERE cloneID=?";
        $stmt = $connection->prepare($sql);

        // Check for prepare error
        if (!$stmt) {
            header("Location: path_details.php?error=stmtfailed");
            exit();
        }

        // Execute the update
        $result = $stmt->execute([$updateTitle, $updateDescription, $userID, $cloneID]);

        // Check for execute error
        if (!$result) {
            
            header("Location: path_details.php?error=update_failed");
            exit();
        }
        

            // Update existing urls 
            $sqlUrls = "UPDATE urls SET urlTitle=?, urlLink=? WHERE urlID=?";
            $stmtUrls = $connection->prepare($sqlUrls);

            // Check for prepare error
            if (!$stmtUrls) {
                header("Location: path_details.php?error=stmtfailed");
                exit();
            }

            $selectSql = "SELECT * FROM urls WHERE cloneID = ?";
            $stmtSelect = $connection->prepare($selectSql);
            // Check for prepare error
            if (!$stmtSelect) {
                header("Location: path_details.php?error=stmtfailed");
                exit();
            }
            $stmtSelect->execute([$cloneID]);
            $urls = $stmtSelect->fetchAll();
            //var_dump( $urls);
            
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
                        header("Location: path_details.php?error=urlupdatefailed");
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
                    $insertSql = "INSERT INTO urls (cloneID, urlTitle, urlLink) VALUES (?, ?, ?)";
                    $stmtInsert = $this->connect()->prepare($insertSql);
            
                    if (!$stmtInsert) {
                        header("Location: path_details.php?error=urlinsertfailed");
                        exit();
                    }
            
                    // Execute the insert
                    $resultInsert = $stmtInsert->execute([$cloneID, $newUrlTitle, $newUrlLink]);
            
                    // Check for execute error
                    if (!$resultInsert) {
                        header("Location: path_details.php?error=urlinsertfailed");
                        exit();
                    }
                
                }
            }

        //debug
        $testsql = "SELECT * from clone_paths JOIN urls ON clone_paths.cloneID = urls.cloneID WHERE clone_paths.cloneID =?";
        $stmtTest = $connection->prepare($testsql);
        $stmtTest->execute([$cloneID]);
        $test = $stmtTest->fetchAll();
        
        $stmtTest =null;
        // Close the statements
        $stmtInsert = null;        
        $stmtSelect = null;
        $stmt = null;
        $stmtUrls = null;


       // print 'count:'. $count. '<br>'; print 'orgin:'.$numOfOrigin.'<br>'; print'update:'. $numOfUpdate . '<br>';
         //Redirect if everything was successful
        //header("Location: pathsManager.php?error=none");
       // header("Location: view.php");
        //exit();
        return $test;
    }
    
    //delete CLONEpath's info


    public function deleteSpecificClonePath1($cloneID) {
        // Delete child record urls
        $connection = $this->connect();
        $sqlUrls = "DELETE FROM urls WHERE cloneID = ?";
        $stmtUrls = $connection->prepare($sqlUrls);
    
        // Delete learning path record
        $sql = "DELETE FROM clone_paths WHERE cloneID = ?";
        $stmt = $connection->prepare($sql);
    
        try {
            // Begin a transaction
            $connection->beginTransaction();
    
            // Delete URLs
            if (!$stmtUrls->execute([$cloneID])) {
                throw new Exception("Error deleting URLs");
            }
    
            // Delete learning path
            if (!$stmt->execute([$cloneID])) {
                throw new Exception("Error deleting learning path");
            }
    
            // Commit the transaction if everything is successful
            $connection->commit();
    
            
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $connection->rollBack();
    
            // Handle the error appropriately
            header("Location: path_details.php?error=delete_failed");
            exit();
        } finally {
            // Close statements
            $stmt = null;
            $stmtUrls = null;
        }
    }


    
    //delete learningpath's info


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
    
            
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $connection->rollBack();
    
            // Handle the error appropriately
            header("Location: view.php?error=delete_failed");
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
                // Alert already voted case
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
                
                header("Location: index.php?error=not_voted_yet");
                exit();
            }
        }
    }
    
    //Handle Cloning Path
    public function setClonePath($userID, $originalPathID) {
        // Fetch original path data
        $originalPathData = $this->getSpecificPath($originalPathID);
    
        //var_dump( $originalPathData);
        if (!$originalPathData) {
            echo "Original path not found.";
            header("Location: index.php?error=pathnotfound");
        }
    
        // Extract data from the original path
        foreach ($originalPathData as $key=> $path) {
            $title = $path['title'];
            $description = $path['description'];
            $userID = $path['userID'];
        }
    
        // Fetch original urls
        $originalUrls = $this->getUrls($originalPathID);
        //var_dump( $originalUrls);
       
        // Extract data from the original urls
        if(!$originalUrls){
            echo "Original Urls not found.";
            header("Location: index.php?error=urlsnotfound");
        }
        foreach ($originalUrls as $key => $url) {
            $urlTitles[] = $url['urlTitle'];
            $urlLinks[] = $url['urlLink'];
        }
        
        // Insert URLs associated with the cloned path
        $this->insertCloneUrls( $urlTitles, $urlLinks);
    
        // Insert the clone into clone_paths table
        $clonePathID = $this->insertClonePath($title, $description, $originalPathID, $userID);
       // echo $clonePathID;
        
        if ($clonePathID !== null) {
            // Store the cloneID in a session variable
            $_SESSION['cloneID'] = $clonePathID;
       
            echo "Clone path added successfully!";
            header("Location: path_details.php?clone=$clonePathID");
        } else {
            // Handle the case where the clone path insertion fails
            echo "Failed to add clone path.";
            header("Location: index.php?error=clonefailed");

        }
    }
    
    private function insertCloneUrls($urlTitles, $urlLinks) {
        $sql = "INSERT INTO urls ( urlTitle, urlLink) VALUES ( ?, ?)";
        $stmt = $this->connect()->prepare($sql);
    
        foreach ($urlTitles as $key => $urlTitle) {
            $urlLink = $urlLinks[$key];
    
            // Insert URLs associated with the cloned path
            if (!$stmt->execute([ $urlTitle, $urlLink])) {
                echo "Failed to insert URL: $urlTitle";
                //header("Location: index.php?error=stmtfailed");

            }
        }
    }

    private function insertClonePath($title, $description, $originalPathID, $userID) {
        $sql = "INSERT INTO clone_paths (cloneTitle, cloneDescription, originalPathID, userID) VALUES (?, ?, ?, ?)";
        $connection = $this->connect();
        $stmt = $connection->prepare($sql);
        
        if ($stmt->execute([$title, $description, $originalPathID, $userID])) {
            // Return the last inserted ID (clonePathID)
            return $connection->lastInsertId();
        } else {
            // Handle the case where the clone path insertion fails
            echo "Failed to insert clone path.";
            return null;
        }
    }
    
    
    
    //Handle Sharing Path
    
    public function generateUniqueIdentifier($originalPathID)
    {
        // generate using md5
        return md5($originalPathID);
    }

    // Function to retrieve path information by the unique identifier
    public function getPathByUniqueIdentifier( $uniqueIdentifier)
    {
        try{
            $stmt = $this->connect()->prepare("SELECT pathID FROM learning_paths WHERE uniqueIdentifier = ?");
            
            $stmt->execute([$uniqueIdentifier]);
        } catch (PDOException $e){
            echo "Error in " . $e->getFile() . " on line " . $e->getLine() . ": " . $e->getMessage();
            exit();
        }
        return $stmt->fetchAll();
    }

    // Store the unique identifier in the database 
    public function storeIdentifier($originalPathID,$uniqueIdentifier ){
        try{
            $stmt = $this->connect()->prepare("UPDATE learning_paths SET uniqueIdentifier = ? WHERE pathID = ?");
          
            $stmt->execute([$uniqueIdentifier,$originalPathID ]);
        } catch (PDOException $e){
            echo "Error in " . $e->getFile() . " on line " . $e->getLine() . ": " . $e->getMessage();
            exit();
        }
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
               
                echo '<form action="share-handler.php" method="post">';
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

     //view specific path
     public  function viewSpecificPath($pathID){
      
        $rows = $this->getSpecificPath($pathID);
        //var_dump($rows);
        foreach( $rows as $row){
            $pathID = $row['pathID'];
            $title = $row['title'];
            $description = $row['description'];
        }
    
        // HTML content for each card
        echo '<div class="card p-5">';
        echo '<h3 class="card-header">Title: ' . htmlspecialchars($title) . '</h3>'; 
       
        echo '<div class="card-body">' ;
        echo '<h4 class="card-title">Description: ' . htmlspecialchars($description).'</h4>';
       
        echo '<p class="card-text">Links: </p>';
        echo  '</div>';

        // Display associated URLs
        $urls = $this->getUrls($pathID); // fetch URLs for a specific pathID

        echo '<ul class="list-group list-group-flush">';
        foreach ($urls as $url) {
            echo '<li class="list-group-item"><a href="' . htmlspecialchars($url['urlLink']) . '">' . htmlspecialchars($url['urlTitle']) . '</a></li>';
        }
        echo '</ul>';

        
    }
    public function displayClonePathInfo($cloneID) {
        $sql = "SELECT DISTINCT * FROM clone_paths WHERE cloneID = ?";
        $stmt = $this->connect()->prepare($sql);
    
        if ($stmt->execute([$cloneID])) {
            $clonePathInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($clonePathInfo) {
                // Display clone path information
                echo '<div class="card p-5">';
                echo '<h3 class="card-header">' . htmlspecialchars($clonePathInfo['cloneTitle']) . '</h3>';
                echo '<div class="card-body">';
                echo '<h4 class="card-title">' . htmlspecialchars($clonePathInfo['cloneDescription']) . '</h4>';
                echo '<p class="card-text">User ID: ' . htmlspecialchars($clonePathInfo['userID']) . '</p>';
                echo '</div>';
    
                $clonePathID = $clonePathInfo['cloneID'];
                // You may want to fetch and display associated URLs here
                $urls = $this->getUrlsByCloneID($clonePathID);
    
                if ($urls) {
                    echo '<ul class="list-group list-group-flush">';
                    foreach ($urls as $url) {
                        echo '<li class="list-group-item"><a href="' . htmlspecialchars($url['urlLink']) . '">' . htmlspecialchars($url['urlTitle']) . '</a></li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p class="card-text">No associated URLs.</p>';
                }
    
                echo '<div class="container-fluid">';
                // Display update and delete buttons
                echo '<div class="row">';
                echo '<div class="col-sm-6">';
                echo '<form action="update-clone-handler.php" method="post">';
                echo '<input type="hidden" name="cloneID" value="' . $clonePathID . '">';
                echo '<button type="submit" class="btn btn-warning mt-3" name="update">Update</button>';
                echo '</form>';
                echo '</div>';
    
                echo '<div class="col-sm-6">';
                echo '<form action="update-clone-handler.php" method="post">';
                echo '<input type="hidden" name="cloneID" value="' . $clonePathID . '">';
                echo '<button type="submit" class="btn btn-danger mt-3" name="delete">Delete</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
    
                echo '</div>'; // Close the container
    
                echo '</div>'; // Close the card
            } else {
                echo "Clone path not found.";
            }
        } else {
            echo "Error fetching clone path information.";
        }
    }
    

    
}