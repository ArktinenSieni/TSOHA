<?php

class Category extends BaseModel {
    //Kategorian model
    
    public $id, $name, $account_id;
    
    public function __construct($attributes = null) {
        parent::__construct($attributes);
        $this->validators = (Array('validate_name'));
    }
    
    //Palauttaa kaikki annetun parametrin käyttäjän kaikki kategoriat
    public static function all($account_id) {
        $attribute = 'SELECT * FROM Category WHERE account_id = :account_id';
        $query = DB::connection()->prepare($attribute);
        $query->execute(array('account_id' => $account_id));
        
        $rows = $query->fetchAll();
        $categories = array();
        
        foreach ($rows as $row) {
            if ($row['account_id'] == $account_id) {
                $categories[] = new Category(Array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'account_id' => $row['account_id']
                ));
            }
        }
        
        return $categories;
    }
    
    //Hakee tietokannasta kategorian sen id:n perusteella
    public static function find($id) {
        $attribute = 'SELECT * FROM Category WHERE id = :id';
        $query = DB::connection()->prepare($attribute);
        $query->execute(array('id' => $id));
        
        $row = $query->fetch();
        $category = new Category(Array(
            'id' => $row['id'],
            'name' => $row['name'],
            'account_id' => $row['account_id']
        ));
        
        return $category;
    }
    
    //hakee alikategoriat
    public static function subcategories($id) {
        $statement = "SELECT * FROM Sub_category WHERE parent_id = :id";
        $query = DB::connection()->prepare($statement);
        $query->execute(array('id' => $id));
        
        $rows = $query->fetchAll();
        $subcategories = Array();
        
        foreach ($rows as $row) {
            $child_id = $row['child_id'];
            $subcategories[] = self::find($child_id);
        }
        
        return $subcategories;
    }
    
    //Hakee ylikategoriat
    public static function parentcategories($id) {
        $statement = "SELECT * FROM Sub_category WHERE child_id = :id";
        $query = DB::connection()->prepare($statement);
        $query->execute(array('id' => $id));
        
        $rows = $query->fetchAll();
        $parentcategories = Array();
        
        foreach ($rows as $row) {
            $parent_id = $row['parent_id'];
            $parentcategories[] = self::find($parent_id);
        }
        
        return $parentcategories;
    }
    
    
    
    //Tallentaa lisätyn kategorian
    public function save() {
        $statement = 'INSERT INTO Category(name, account_id) VALUES (:name, :account_id) RETURNING id';
        $query = DB::connection()->prepare($statement);
        
        $query->execute(array('name'=> $this->name, 'account_id' => $this->account_id));
        
        $row = $query->fetch();
        
        $this->id = $row['id'];
    }
    
    public function delete() {
        $id = $this->id;
        $substatement = 'DELETE FROM Sub_Category where parent_id = :id OR child_id = :id';
        $query = DB::connection()->prepare($substatement);
        $query->execute(array('id' => $id));
        
        $statement = 'DELETE FROM Category WHERE id = :id';
        $query = DB::connection()->prepare($statement);
        
        
        $query->execute(array('id' => $id));
    }
    
    //tekee parent-child yhteyden kahden kategorian välille
    public function save_connection($parent_id, $child_id) {
        $statement = 'INSERT INTO Sub_category(parent_id, child_id) VALUES (:parent_id, :child_id)';
        $query = DB::connection()->prepare($statement);
        
        $query->execute(array('parent_id' => $parent_id, 'child_id' => $child_id));
    }
    
    //poistaa parent-child yhteyden kategorialta
    public function remove_connection($child_id) {
        $statement = 'DELETE FROM Sub_Category WHERE parent_id = :parent_id AND child_id = :child_id';
        $query = DB::connection()->prepare($statement);
        
        $parameters = array('parent_id' => $this->id, 'child_id' => $child_id);
        $query->execute($parameters);
    }
    
    //Validoi kategorian nimi-attribuutin.
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
}
