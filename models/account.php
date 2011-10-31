<?php

class Account {

    private $data = array();

    function __construct($data = array()) {
        $this->data = $data;
    }

    public static function select($where = '`joindate` < @joindate AND `last_login` = @lastlogin') {
        $result = DatabaseManager::get('auth')->query_and_fetch_all("SELECT `id` FROM `account` WHERE $where;");
        $accounts = false;
        if (count($result) > 0) {
            $accounts = array();
            foreach ($result as $row) {
                $accounts[] = new Account($row);
            }
        }
        return $accounts;
    }

    function get_characters(){
        return Character::select('account = ' . $this->data['id']);
    }
    
    function delete(){
        $this->delete_authdb_records();
        $this->delete_chardb_records();
        $this->delete_characters();
    }
    
    function delete_authdb_records() {

        if (empty($this->data['id']))
            return false;

        $id = $this->data['id'];
        $queries = array(
            "DELETE FROM `account` WHERE `id`= $id;",
            "DELETE FROM `account_access` WHERE `id`= $id;",
            "DELETE FROM `account_banned` WHERE `id`= $id;",
            "DELETE FROM `realmcharacters` WHERE `acctid`= $id;"
        );
        DatabaseManager::get('auth')->queue->add_range($queries);
        return true;
    }

    function delete_chardb_records() {

        if (empty($this->data['id']))
            return false;

        $id = $this->data['id'];
        $queries = array(
            "DELETE FROM `account_data` WHERE `id`= $id;",
            "DELETE FROM `account_instance_times` WHERE `id`= $id;",
            "DELETE FROM `account_tutorial` WHERE `id`= $id;"
        );
        DatabaseManager::get('char')->queue->add_range($queries);
        return true;
    }
    
    function delete_characters(){
        if (empty($this->data['id']))
            return false;

        $characters = $this->get_characters();
        foreach($characters as $char){
            if(!$char->delete()){
                return false;
            }
        }
        return true;
    }

}

