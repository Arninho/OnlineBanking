<?php

class User {

    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function update($fields = array(), $id = null) {

        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->ID;
        }


        if (!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating an accont.');
        }
    }
    
    public function send($fields = array()) {
        if (!$this->_db->insert('transactionitems', $fields)) {
            throw new Exception('There was a problem with inserting transactionitem.');
        }
    }

    public function find($user = null) {
        if ($user) {
            $field = (is_numeric($user) ? 'ID' : 'UserName');
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->result()->fetch_object();
                return true;
            }
        }
        return false;
    }
    
    public function getAccounts($userid){
     if ($userid) {
            $data = $this->_db->get('accounts', array('User_ID', '=', $userid));

            if ($data->count()) {
               return $data->result()->fetch_all();
            }
        }
        return $data->result();
    }
    
    public function getTransactions($tranid){
     if ($tranid) {
            $data = $this->_db->get('transactionitems', array('Transaction_ID', '=', $tranid));

            if ($data->count()) {
               return $data->result()->fetch_all();
            }
        }
        return $data->result();
    }
    
    public function getAccAmount($accid){
        if ($accid) {
            $data = $this->_db->getAmount('accounts', array('ID', '=', $accid));

            if ($data->count()) {
               return $data->result()->fetch_object();
            }
        }
        return 0;
    }
    
    public function getAccByID($accid){
        if ($accid) {
            $data = $this->_db->getCode('accounts', array('ID', '=', $accid));

            if ($data->count()) {
               return $data->result()->fetch_object();
            }
        }
        return 0;
    }

    public function getTranByAccID($accid){
        if ($accid) {
            $data = $this->_db->getID('transactions', array('Account_ID', '=', $accid));

            if ($data->count()) {
               return $data->result()->fetch_object();
            }
        }
        return 0;
    }
    public function getAccByUserID($userid){
        if ($userid) {
            $data = $this->_db->getID('accounts', array('User_ID', '=', $userid));

            if ($data->count()) {
               return $data->result()->fetch_object();
            }
        }
        return 0;
    }
    public function getUserByAccID($accid){
        if ($accid) {
            $data = $this->_db->getUserID('accounts', array('ID', '=', $accid));

            if ($data->count()) {
               return $data->result()->fetch_object();
            }
        }
        return 0;
    }


    public function getAccByCode($code){
         if ($code) {
            $data = $this->_db->getID('accounts', array('Code', '=', $code));

            if ($data->count()) {
               return $data->result()->fetch_object();
            }
        }
        return 0;
    }

    public function login($username = null, $password = null, $remember = false) {
        $user = $this->find($username);
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->ID);
        } else {
            $user = $this->find($username);

            if ($user) {
                if ($this->data()->Password === Hash::make($password, $this->data()->Salt)) {
                    Session::put($this->_sessionName, $this->data()->ID);
                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', array('User_ID', '=', $this->data()->ID));
                        if (!$hashCheck->count()) {
                            $this->_db->insert('users_session', array(
                                'User_ID' => $this->data()->ID,
                                'Hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->Hash;
                        }
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function exists() {
        return(!empty($this->_data)) ? true : false;
    }

    public function logout() {
        $this->_db->delete('users_session', array('User_ID', '=', $this->data()->ID));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data() {
        return $this->_data;
    }

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }

}
