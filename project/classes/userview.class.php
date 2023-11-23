<?php

class UserView extends Login{

    
    private $sessionid;


    public function __construct($sessionid)
    {
        $this->sessionid=$sessionid;
 
    }
    public function helloName($sessionid){
        $result = $this->getUserName($sessionid);
        echo "<p class='display-5 p-2'> Hello, " . $result .'!</p>';
    }
}