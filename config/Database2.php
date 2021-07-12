<?php

    // class Database {
    //     private $host = "127.0.0.1";
    //     private $database_name = "rest_php_api";
    //     private $username = "root";
    //     private $password = "";

    //     public $conn;

    //     public function getConnection(){
    //         $this->conn = null;
    //         try{
    //             $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
    //             $this->conn->exec("set names utf8");
    //         }catch(PDOException $exception){
    //             echo "Database could not be connected: " . $exception->getMessage();
    //         }
    //         return $this->conn;
    //     }
    // }  

    class Database {

        private $Host;
        private $db_name;
        private $Username;
        private $Password;
        private $conn;
   

        public function getConnection(){

            $this->host = 'localhost';
            $this->db_name = 'rest_php_api';
            $this->Username = 'root';
            $this->password = '';

            $this->conn = new mysqli($this->host, $this->Username, $this->password, $this->db_name );
            
            if ($this->conn->connect_errno) {
                print_r($this->conn->connect_error);
                exit;
            }else {
                 return $this->conn;
                
            }
        
         }
    }  
