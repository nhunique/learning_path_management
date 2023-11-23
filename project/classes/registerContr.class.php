<?php

class RegisterContr extends Register{
    private $email;
    private $firstName;
    private $lastName;
    private $password;
    
    public function __construct($email, $fn, $ln, $pwd)
    {
        $this->email=$email;
        $this->firstName=$fn;
        $this->lastName=$ln;
        $this->password=$pwd;
    }

    //sign up user
    public function registerUser(){
        if($this->emptyInput() ==  true){
            header("Location: ../project/register.php?error=emptyinput");
            exit();
        }
        if($this->isValidEmail() ==  false){
            header("Location: ../project/register.php?error=invalidemail");
            exit();
        }
        if($this->isUserTaken() ==  true){
            header("Location: ../project/register.php?error=usertaken");
            exit();
        }

        //set user
        $this->setUser($this->email, $this->firstName, $this->lastName, $this->password);
       
    }

     //validation and error handler

     private function emptyInput(){
        $result ='';
        if(empty($this->email ) || empty($this->firstName ) ||empty($this->lastName ) || empty($this->password ) ){
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


    private function isUserTaken(){
        if($this->checkUser($this->email, $this->password)){
            return true;
        } else {
            return false;
        }
    }


    //Check error code
    public function checkErrorCode($errorCode, $registerCode){

        // Check if the "error" parameter is present in the URL
        if (isset($_GET['error'])) {
            $errorCode = $_GET['error'];
            if ($_GET['register']) {
                $registerCode = $_GET['register'] ;
                // Display something based on the error code
                switch ($errorCode) {
                    case 'none':
                        if ($_GET['register'] == "successs") {
                            //going to login page
                            header("Location: ../project/login.php");
                        }
                        //going back to front page
                        header("Location: ../project/index.php?error=none");
                        break;
                    case 'invalidemail':
                        echo "Invalid email error!";
                        header("Location: ../project/register.php?error=invalidemail");
                        break;
                    case 'usertaken':
                        echo "User taken error!";
                        header("Location: ../project/register.php?error=usertaken");
                        break;
                    case 'emptyinput':
                        echo "Empty input error!";
                        header("Location: ../project/register.php?error=emptyinput");
                        break;
                    case 'wrongpassword':
                        echo "Wrong Password error!";
                        header("Location: ../project/register.php?error=wrongpassword");
                        break;
                    default:
                        echo "Unknown error!";
                        break;
                }
            }
        }
    }


}