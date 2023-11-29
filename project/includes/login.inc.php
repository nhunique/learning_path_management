<?php

//grabbing user input
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['login'])){

        $email         = $_POST['email'];
        $password      = $_POST['password'];

       
        //instanciate userContr class
        $login =  new LoginContr($email, $password);
        

        //$login->checkErrorCode($_GET['error'], $_GET['register']);

        //Running error handler and user login
        $login->loginUser($email, $password);
        echo 
        //going back to front page
        header("Location: index.php?error=none");
    

    }
}