<?php

class LearningPathContr extends LearningPath{

    private $pathID;
    private $title;
    private $description;
    private $email;
    private $urlTitles = [];
    private $urlLinks = [];

    public function __construct($title, $description, $email, $urlTitles, $urlLinks)
    {
        $this->email=$email;
        $this->title=$title;
        $this->description=$description;
        $this->urlTitles = $urlTitles;
        $this->urlLinks = $urlLinks;
    }


     //validation and err handling
    private function emptyInput(){
        $result ='';
        if(empty($this->title )|| empty($this->description ) || empty($this->urlTitles ) || empty($this->urlLinks)){
            $result = true;
        }
        else{
            $result = false;
        }
        return $result;
    }




    
    //create learing path
    public function createLearningPath(){

        if($this->emptyInput() ==  true){
            header("Location: ../project/pathsManager.php?error=emptyinput");
            exit();
        }
      

        //set path
       
        $this->setLearningPath( $this->title, $this->description, $this->email, $this->urlTitles, $this->urlLinks);
        
    }

    //view specific path
    public static function viewSpecificPath($pathID){
        $learningPath = new LearningPath();
        $rows = $learningPath->getSpecificPath($pathID);
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
        $urls = $learningPath->getUrls($pathID); // fetch URLs for a specific pathID

        echo '<ul class="list-group list-group-flush">';
        foreach ($urls as $url) {
            echo '<li class="list-group-item"><a href="' . htmlspecialchars($url['urlLink']) . '">' . htmlspecialchars($url['urlTitle']) . '</a></li>';
        }
        echo '</ul>';

        
    }

     //view learing path
    public static function viewLearningPath($email){

        
        $learningPath = new LearningPath();
        $rows = $learningPath->getLearningPath($email);
        //echo '<pre>';
        //var_dump($rows);
        foreach ($rows as $path) {
            $title = $path['title'];
            $description = $path['description'];
            $pathID = $path['pathID'];

            // HTML content for each card
            echo '<div class="card p-5">';
            echo '<h3 class="card-header">' . htmlspecialchars($title) . '</h3>'; // Fix: Added the missing equal sign
            echo '<div class="card-body">' ;
            echo '<h4 class="card-title">' . htmlspecialchars($description).'</h4>';
            echo '<p class="card-text">Links: </p>';
            echo  '</div>';

            // Display associated URLs
            $urls = $learningPath->getUrls($pathID); // fetch URLs for a specific pathID
            //echo '<pre>';
            //var_dump($urls);
            
            echo '<ul class="list-group list-group-flush">';
            foreach ($urls as $url) {
                echo '<li class="list-group-item"><a href="' . htmlspecialchars($url['urlLink']) . '">' . htmlspecialchars($url['urlTitle']) . '</a></li>';
            }
            echo '</ul>';


            // Update and Delete buttons
            echo '<div class="d-flex">';
            echo '<form action="update.php" method="post">';
            echo '<input type="hidden" name="pathID" value="' . $pathID . '">';
            echo '<button class="btn btn-primary mt-3"  >Update</button>';
            echo '</form>';

            echo '<form action="delete.php" method="post">';
            echo '<input type="hidden" name="pathID" value="' . $pathID . '">';
            echo '<button class="btn btn-danger mt-3 ">Delete</button>';
            echo '</form>';

            echo '</div>';
            echo '</div>'; // Close the card
        }
    }


    
    



}