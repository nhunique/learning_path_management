<?php

class LoginContr extends Login{

    private $email;
    private $password;
    
    public function __construct($email, $pwd)
    {
        $this->email=$email;
        $this->password=$pwd;
    }


     //validation and err handling
    private function emptyLoginInput(){
        $result ='';
        if(empty($this->email ) || empty($this->password ) ){
            $result = true;
        }
        else{
            $result = false;
        }
        return $result;
    }


    private function isValidEmail() {
        if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else{
            return false;
        }
    }


    
    //log user in
    public function loginUser(){

        if($this->emptyLoginInput() ==  true){
            header("Location: ../project/login.php?error=emptyinput");
            exit();
        }
        if($this->isValidEmail() ==  false){
            header("Location: ../project/login.php?error=invalidemail");
            exit();
        }
        

        //get user
        $users = $this->getUser($this->email, $this->password);
        return $users;
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
                    header("Location: ../project/index.php?error=none");
                    break;
                case 'invalidemail':
                    echo "Invalid email error!";
                   
                    break;
                case 'emptyinput':
                    echo "Empty input error!";
              
                    break;
                case 'wrongpassword':
                    echo "Wrong Password error!";
               
                    break;
                default:
                    echo "Unknown error!";
                    break;
            }
        }

    }

}