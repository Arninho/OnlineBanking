<?php
class DB{
    private static $_instance = null;
    private $_con, 
            $_query,
            $_error = false,
            $_results,
            $_count = 0;
    
    private function __construct() {
        try{
            $this->_con = new mysqli(Config::get('mysql/host'),Config::get('mysql/username'),Config::get('mysql/password'),Config::get('mysql/db'));
            echo 'Connected';
        }
        catch (mysqli_sql_exception $e){
        die($e->getMessage());
        }
    }
    
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    
}

