<?php

namespace Core;

use Core;

class BaseModel {

    public $db;

    /**
     * Connects any model to database
     */
    public function __construct(){
        $database= new Database();
        $this->db= $database->connect();
    }

}

?>