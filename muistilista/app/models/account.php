<?php

class Account extends BaseModel{
    public $id, $name, $password;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
}

