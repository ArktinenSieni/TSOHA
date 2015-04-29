<?php

class Chore extends BaseModel {

    //Askareen malli

    public $id, $name, $category_id, $priority, $status, $account_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_priority');
    }

    //Palauttaa kaikki parametrina annetun käyttäjän askareet
    public static function all($account_id) {
        $attribute = 'SELECT * FROM Chore WHERE account_id = :account_id';
        $query = DB::connection()->prepare($attribute);
        $query->execute(array('account_id' => $account_id));

        $rows = $query->fetchAll();
        $chores = array();

        foreach ($rows as $row) {
            if ($row['account_id'] == $account_id) {
                $chores[] = new Chore(Array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'category_id' => $row['category_id'],
                    'priority' => $row['priority'],
                    'status' => $row['status'],
                    'account_id' => $row['account_id']
                ));
            }
        }

        return $chores;
    }

    //Palauttaa kaikki parametrina annetun käyttäjän ja kategorian askareet
    public static function categorized_all($account_id, $category_ids) {
        $chores = array();
        foreach ($category_ids as $category_id) {
            $attribute = 'SELECT * FROM Chore WHERE account_id = :account_id AND category_id = :category_id';
            $query = DB::connection()->prepare($attribute);
            $query->execute(array('account_id' => $account_id, 'category_id' => $category_id));

            $rows = $query->fetchAll();

            foreach ($rows as $row) {
                if ($row['account_id'] == $account_id) {
                    $chores[] = new Chore(Array(
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'category_id' => $row['category_id'],
                        'priority' => $row['priority'],
                        'status' => $row['status'],
                        'account_id' => $row['account_id']
                    ));
                }
            }
        }

        return $chores;
    }

    //Palauttaa tietokannasta askareen sen id:n perusteella
    public static function find($id) {
        $attribute = 'SELECT * FROM Chore WHERE id = :id LIMIT 1';
        $query = DB::connection()->prepare($attribute);
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $chore = new Chore(Array(
                'id' => $row['id'],
                'name' => $row['name'],
                'category_id' => $row['category_id'],
                'priority' => $row['priority'],
                'status' => $row['status'],
                'account_id' => $row['account_id']
            ));

            return $chore;
        }

        return null;
    }

    //Tallentaa tietokantaan uuden askareen annetun parametrina annetulle käyttäjälle
    public function save($user_id) {
        $statement = 'INSERT INTO Chore (name, category_id, priority, account_id) VALUES (:name, :category_id, :priority, :account_id) RETURNING id';
        $query = DB::connection()->prepare($statement);

        $query->execute(array('name' => $this->name, 'category_id' => $this->category_id, 'priority' => $this->priority, 'account_id' => $user_id));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    //Päivittää tietokannassa olemassa olevaa askaretta
    public function update() {
        $statement = 'UPDATE Chore SET name = :name, priority = :priority, category_id = :category_id WHERE id = :id RETURNING id';
        $query = DB::connection()->prepare($statement);

        $parameters = array('name' => $this->name, 'category_id' => $this->category_id, 'priority' => $this->priority, 'id' => $this->id);
        $query->execute($parameters);

        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    //Vaihtaa kaikkien niiden askareiden kategoriaa joksikin toiseksi, joilla on parametrina annettu kategoria.
    public static function change_category($category_id) {
        $category = Category::find($category_id);
        $statement = 'SELECT * FROM Category WHERE account_id = :account_id AND NOT id = :category_id';
        
        $new_category_query = DB::connection()->prepare($statement);
        $new_category_params = array('account_id' => $category->account_id, 'category_id' => $category->id);
        
        $new_category_query->execute($new_category_params);
        $row = $new_category_query->fetch();
        
        $new_category_id = $row['id'];
        
        $update_statement = 'UPDATE Chore SET category_id = :new_category_id WHERE account_id = :account_id AND category_id = :category_id';
        $update_query = DB::connection()->prepare($update_statement);
        
        $update_params = array('new_category_id' => $new_category_id, 'account_id' => $category->account_id, 'category_id' => $category->id);
        $update_query->execute($update_params);
    }

    //Merkitsee tietokannassa olevan askareen tehdyksi.
    public function finish() {
        $statement = 'UPDATE Chore SET status = true WHERE id = :id RETURNING id';
        $query = DB::connection()->prepare($statement);

        $parameters = array('id' => $this->id);
        $query->execute($parameters);

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    //Poistaa tietokannassa olevan askareen
    public function delete() {
        $statement = 'DELETE FROM Chore WHERE id = :id';
        $query = DB::connection()->prepare($statement);

        $parameters = array('id' => $this->id);
        $query->execute($parameters);
    }

    //Validoi askareen nimi-attribuutin.
    public function validate_name() {
        $errors = array();

        if ($this->validate_is_string_empty($this->name)) {
            $errors[] = 'Name cannot be empty';
        }
        if ($this->validate_is_string_short($this->name, 3)) {
            $errors[] = 'Name has to be at least 3 characters long';
        }
        if ($this->validate_is_string_long($this->name, 20)) {
            $errors[] = 'Name length is 20 characters at maximum';
        }

        return $errors;
    }

    //Validoi askareen prioriteetti-attribuutin.
    public function validate_priority() {
        $errors = array();

        if ($this->validate_is_int_empty($this->priority)) {
            $errors[] = 'Priority cannot be empty';
        }
        if ($this->validate_is_int_small($this->priority, 0)) {
            $errors[] = 'Priority has to be positive number';
        }
        if ($this->validate_is_int_big($this->priority, 10)) {
            $errors[] = 'Priority has to be smaller or equals to 10';
        }

        return $errors;
    }

}
