<?php

//grabbing user input
if(isset($_POST['create'])){

    $email         = $_POST['email'];
    $firstName     = $_POST['firstname'];
    $lastName      = $_POST['lastname'];
    $password      = $_POST['password'];
    $pwd_hash = password_hash($password, PASSWORD_DEFAULT);
    
    //$validExtension = '.png';
    //$photoPath = "uploads/" . $firstname.$lastname.$validExtension;
    

    //instanciate userContr class
  
    $register = new RegisterContr($email, $firstName, $lastName, $password)   ;
    
    //Running error handler and user siignup
    $register->registerUser($email, $firstName, $lastName, $pwd_hash);

  
    //going back to login page
    header("Location: ../project/login.php?error=none&register=success");
    $register->checkErrorCode($_GET['error'], $_GET['register']);
}
