<?php

class Category extends BaseModel {
    //Kategorian model
    
    public $id, $name, $account_id;
    
    public function __construct($attributes = null) {
        parent::__construct($attributes);
        $this->validators = (Array());
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
        $attribute = 'SELECT * FROM Category WHERE id = :id LIMIT 1';
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
}
