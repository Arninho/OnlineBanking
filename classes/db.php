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
    
    public function query($sql,$params = array()){
        $this->error = false;
        if($this->_query = $this->_con->prepare($sql)){
            $index = 1;
            if(count($params)){
                foreach($params as $param){
                    $this->_query->bindValue($index, $param);
                    $index++;
                }
            }
            if($this->_query->execute()){
                $this->_results = $this->_query->fetch(mysqli::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            }
            else{
                $this->_error = true;
            }
        }
        return $this;
    }
    
    public function action($action, $table, $where = array()){
        if(count($where)=== 3){
            $operators = array('=', '>', '<', '>=', '<=');
            
            $field          = $where[0];
            $operator       = $where[1];
            $value          = $where[2];
            
            if(in_array($operator, $operators)){
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        return false;
    }
    
    public function get($table, $where){
        return $this->action('SELECT *', $table, $where);
    }
    
    public function delete($table, $where){
        return $this->action('DELETE', $table, $where);
    }
    
    public function insert($table, $fields = array()){
            $keys = array_keys($fields);
            $values = null;
            $index = 1;
            
            foreach ($fields as $field){
                $values .= '?';
                if($index <count($fields)){
                    $valuse .=', ';
                }
                $index++;
            }
            
            $sql = "INSERT INTO {$table} (`". implode('`, `', $keys) ."`) VALUES ({$values})";
        
            if(!$this->query($sql, $fields)->error()){
                return true;
            }
            
        return false;
    }
    
    public function update($table, $id, $fields){
        $set ='';
        $index = 1;
        
        foreach($fields as $name => $value){
            $set .="{$name} = ?";
            if($index < count($fields)){
                $set .= ', ';
            }
            $index++;
        }
        
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        
        if(!$this->query($sql, $fields)->error()){
            return true;
        }
        
        return false;
    }
    
    public function result(){
        return $this->_results;
    }
    
    public function error(){
        return $this->_error;
    }
    
    public function count(){
        return $this->_count;
    }
}