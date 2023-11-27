<?php

class Login extends Dbh{
    
    //get a user with email, passwd
    protected function getUser($email, $password){

        //grab password from database
        $sql = "SELECT password_hash FROM users WHERE email=? ";
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
        
        $password_hash = $rows[0]['password_hash'];

        //compare userPassword and databasePassword
        $checkPassword = password_verify($password, $password_hash);

        if($checkPassword == false){
            $stmt = null;
            header("Location: ../project/login.php?error=wrongpassword");
            exit();
        } elseif ($checkPassword == true){
        //check if email  & password both valid
            $sql = "SELECT * FROM users WHERE email= ? AND password_hash = ? ";
            $stmt = $this->connect()->prepare($sql);

            //Check if stmt not execute
            if(!$stmt->execute([$email, $password_hash])){
                //close stmt
                $stmt = null;
                header("Location: ../project/login.php?error=stmtfailed");
                exit();
            }

            //again ,if user found in database
            if($stmt->rowCount() == 0){
               
                $stmt = null;
                header("Location: : ../project/login.php?error=usernotfound");
                exit();
            }

            //log in the user and start user session
            $users = $stmt->fetchALL();
            session_start();
            $_SESSION['email'] = $users[0]['email'];
            
            //clear stmt
            $stmt=null;

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