<?php

class Login extends Dbh {

    // get a user with email, passwd

    protected function getUser($email, $password) {
        // grab password from database
        $sql = "SELECT * FROM users WHERE email= ?";
        $stmt = $this->connect()->prepare($sql);

        // Check if stmt executes
        if (!$stmt->execute([$email])) {
            // close stmt
            $stmt = null;
            header("Location: ../project/login.php?error=stmtfailed");
            exit();
        }

        // Fetch the result
        $rows = $stmt->fetchAll();

        // Check if user exists in the database
        if (count($rows) == 0) {
            $stmt = null;
            header("Location: ../project/login.php?error=usernotfound");
            exit();
        }

        // Get the password hash from the result
        $password_hash = $rows[0]['password_hash'];

        // Compare userPassword and databasePassword
        $checkPassword = password_verify($password, $password_hash);

        if ($checkPassword == false) {
            $stmt = null;
            header("Location: ../project/login.php?error=wrongpassword");
            exit();
        } elseif ($checkPassword == true) {
            // Log in the user and start the user session
            session_start();
            $_SESSION['email'] = $email;
            var_dump($_SESSION);
            // clear stmt
            $stmt = null;
        }
    }



    //get a user name
    protected function getUserName($email){

        //grab password from database
        $sql = "SELECT firstName, lastName FROM users WHERE email=? ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$email]);

        $rows = $stmt->fetchALL(); 

        //Check if stmt execute
        if(!$stmt->execute([$email])){
            //close stmt
            $stmt = null;
            header("Location: ../project/login.php?error=stmtfailed");
            exit();
        }

        //check if user not exits in database
        if($stmt->rowCount() == 0){
            $stmt = null;
            header("Location: ../project/login.php?error=usernotfound");
            exit();
        }
        
        return $rows[0]['firstName'] . " ".$rows[0]['lastName'] ;
    }
    


    protected function checkUser($email){

        $sql = "SELECT email FROM users WHERE email=? ";
        $stmt = $this->connect()->prepare($sql);

        //Check if stmt execute
        if(!$stmt->execute([$email])){
            //close stmt
            $stmt = null;
            header("Location: ../project/login.php?error=stmtfailed");
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

    

}