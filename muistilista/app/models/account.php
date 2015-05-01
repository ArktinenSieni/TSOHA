<?php

class Account extends BaseModel {
    //Käyttäjän malli

    public $id, $name, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);        
    }

    //Varmentaa tietojensa perusteella userin tiedot oikeiksi tai vääriksi
    public function authenticate($username, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Account WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $username, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $user = new Account(Array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password']
            ));
            return $user;
        } else {
            return null;
        }
    }

    //Hakee accountin tietokannasta
    public static function find($account_id) {
        $query = DB::connection()->prepare('SELECT * FROM Account WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $account_id));
        $row = $query->fetch();

        $user = new Account(Array(
            'id' => $row['id'],
            'name' => $row['name'],
            'password' => $row['password']
        ));
        return $user;
    }
    
    //Tallentaa accountin tietokantaan
    public function save() {
        $statement = 'INSERT INTO Account (name, password) VALUES (:name, :password)';
        $query = DB::connection()->prepare($statement);
        
        $query->execute(array('name' => $this->name, 'password' => $this->password));
    }
    
    public function errors($password_verification) {
        $errors = array_merge($this->validate_name(), $this->validate_password($password_verification));
        
        return $errors;
    }
    
    public function validate_name() {
        $errors = array();
        
        if ($this->validate_is_string_empty($this->name)) {
            $erros[] = 'Username can not be empty';
        }
        if ($this->validate_is_string_short($this->name, 5)) {
            $errors[] = 'Username has to be at least 5 characets long';
        }
        if ($this->validate_is_string_long($this->name, 12)) {
            $errors[] = 'Username can not be longer than 12 characters';
        }
        return $errors;
    }
    
    public function validate_password($verification) {
        $errors = array();
        
        if($this->validate_is_string_empty($this->password)) {
            $errors[] = 'Password can not be empty';
        }
        if ($this->validate_is_string_short($this->password, 6)) {
            $errors[] = 'Password has to be at least 6 characters long';
        }
        if ($this->validate_is_string_long($this->password, 18)) {
            $errors[] = 'Password can not be longer than 18 characters';
        }
        if ($this->password != $verification) {
            $errors[] = 'Password does not match the verification';
        }
        
        return $errors;
    }
}
