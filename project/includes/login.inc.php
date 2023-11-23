<?php

//grabbing user input
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['login'])){

        $email         = $_POST['email'];
        $password      = $_POST['password'];

        //instanciate userContr class
        $login =  new LoginContr($email, $password);
        
        //Running error handler and user login
        $login->loginUser($email, $password);


        //going back to front page
        header("Location: ../project/index.php?error=none");
        $login->checkErrorCode($_GET['error'], $_GET['register']);
    

    }
}