<?php
class Validate{
    private $_passed = false,
            $_erros = array(),
            $_db = null;
    
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    public function check($source, $items = array()){
        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){
                
                $value = trim($source[$item]);
                
                if($rule === 'required' && empty($value)){
                    $this->addError("{$rules['name']} kötelező!");
                }else if(!empty($value)){
                    switch ($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$rules['name']} minimum {$rule_value} karakterből kell álljon!");
                            }
                            break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$rules['name']} maximum {$rule_value} karakterből állhat!");
                            }
                            break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$items[$rule_value]['name']} és {$rules['name']} kell egyezzen!");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value,array('$item','=', $value));
                            if($check->count()){
                                $this->addError("{$rules['name']} már létezik!");
                            }
                            break;
                    }
                }
            }
        }
        
        if(empty($this->_erros)){
            $this->_passed = true;
        }
        
        return $this;
    }
    
    private function addError($error){
        $this->_erros[] = $error;
    }
    
    public function errors(){
        return $this->_erros;
    }
    
    public function passed(){
        return $this->_passed;
    }
}


