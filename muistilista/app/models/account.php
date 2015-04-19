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

}
