<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      foreach($this->validators as $validator){
        $validator_error = $this->{$validator}();
        $errors = array_merge($errors, $validator_error);
      }

      return $errors;
    }
    
    public function validate_is_string_empty($string) {
        if ($string == '' || $string == null) {
            return true;
        }
        return false;
    }
    
    public function validate_is_string_long($string, $maxLength) {
        if(strlen($string) > $maxLength) {
            return true;
        }
        return false;
    }
    
    public function validate_is_string_short($string, $minLength) {
        if(strlen($string) < $minLength) {
            return true;
        }
        return false;
    }
    
    public function validate_is_int_empty($int) {
        if($int == null || is_numeric($int) == false) {
            return true;
        }
        return false;
    }
    
    public function validate_is_int_small($int, $min) {
        if($int < $min) {
            return true;
        }
        return false;
    }
    
    public function validate_is_int_big($int, $max) {
        if($int > $max) {
            return true;
        }
        
        return false;
    }

  }
