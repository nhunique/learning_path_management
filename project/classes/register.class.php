<?php

class Register extends Dbh{

    protected function checkUser($email){

        $sql = "SELECT email FROM users WHERE email=? ";
        $stmt = $this->connect()->prepare($sql);

        //Check if stmt execute
        if(!$stmt->execute([$email])){
            //close stmt
            $stmt = null;
            header("Location: ../project/index.php?error=stmtfailed");
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


    //set user's info

    protected function setUser( $email, $firstName, $lastName, $password){

        //hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users ( email, firstName, lastName, password_hash) 
        VALUES(?,?,?,?)";
        $stmt =$this->connect()->prepare($sql);
        
        if(!$stmt->execute([ $email, $firstName, $lastName, $password_hash])){
            $stmt = null;
            header("Location: ../project/index.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
       
    
    }
}