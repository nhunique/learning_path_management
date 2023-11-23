<?php

class LearningPathContr extends LearningPath{

    private $title;
    private $description;
    private $userid;
    private $url = [];
    
    public function __construct($title, $description, $userid, $urls)
    {
        $this->userid=$userid;
        $this->title=$title;
        $this->description=$description;
        $this->urls = $urls;
    }


     //validation and err handling
    private function emptyInput(){
        $result ='';
        if(empty($this->title )|| empty($this->description ) || empty($this->urls )){
            $result = true;
        }
        else{
            $result = false;
        }
        return $result;
    }


    private function isValidUserID() {
        if(filter_var($this->userid, FILTER_VALIDATE_EMAIL)){
            return true;
        } else{
            return false;
        }
    }


    
    //create learing path
    public function createLearningPath(){

        if($this->emptyInput() ==  true){
            header("Location: ../project/pathsManager.php?error=emptyinput");
            exit();
        }
        if($this->isValidUserID() ==  false){
            header("Location: ../project//pathsManager.php?error=invaliduserid");
            exit();
        }
        

        //get user
        $this->setLearningPath($this->userid, $this->title, $this->description,  $this->urls);

    }

    //Check error code
    public function checkErrorCode($errorCode){

        // Check if the "error" parameter is present in the URL
        if (isset($_GET['error'])) {
            $errorCode = $_GET['error'];

            // Display something based on the error code
            switch ($errorCode) {
                case 'none':
                    //going back to front page
                    header("Location: ../project/pathManger.php?error=none");
                    break;
                case 'invaliduserid':
                    echo "Invalid email error!";
                   
                    break;
                case 'emptyinput':
                    echo "Empty input error!";
              
                    break;
                
                default:
                    echo "Unknown error!";
                    break;
            }
        }

    }

}