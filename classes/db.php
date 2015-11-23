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
        //mysqli_report(MYSQLI_REPORT_ALL);
        if($this->_query = $this->_con->prepare($sql)){
            $types = '';   
            if(count($params)){
                foreach($params as $param){
                     if(is_int($param)) {
                        $types .= 'i';              //integer
                    } elseif (is_float($param)) {
                        $types .= 'd';              //double
                    } elseif (is_string($param)) {
                        $types .= 's';              //string
                    } else {
                        $types .= 'b';              //blob and unknown
                    }
                }
                array_unshift($params, $types);
                call_user_func_array(array($this->_query,'bind_param'), $this->makeValuesReferenced($params));
            }
            if($this->_query->execute()){
                $this->_results = $this->_query->get_result();
                $this->_count = $this->_results->num_rows;
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
                    $values .=', ';
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
    
    private function makeValuesReferenced(&$array)
    {
        $refs = array();
        foreach($array as $key => $value){
            $refs[$key] = &$array[$key];
        }
            
        return $refs;
    }
}


