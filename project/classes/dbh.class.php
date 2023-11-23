<?php

class Dbh {

    private $host ;
    private $user ;
    private $pwd ;
    private $dbName ;

    protected function connect(){
        try{
            $this->host = "localhost";
            $this->user = "root";
            $this->pwd = "";    
            $this->dbName ="learningPaths_DB";

            $pdo = new PDO('mysql:host='. $this->host.';port=4306;dbname=' . $this->dbName . ';charset=utf8', $this->user, $this->pwd);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
            
        } catch(PDOEXCEPTION $e){
            print "Error! : " . $e->getMessage() . '<br>';
            die();
        }
        
    }

}