<?php

class ChoreController extends BaseController {
    //Askareiden kontrolleri
    
    //Päivittää yksittäisen userin omistaman askareen tiedot.
    public static function update($id) {
        //Oikeuksien tarkastus
        $params = $_POST;
        $chore = Chore::find($id);
        self::check_priviledges($chore->account_id);
        
        //Attribuuttien taltiointi
        $attributes = Array(
            'name' => $params['name'],
            'category_id' => $params['category'],
            'priority' => $params['priority'],
            'id' => $id
        );
        
        //Virhetarkistus
        $chore = new Chore($attributes);
        $errors = $chore->errors();

        if (count($errors) > 0) {
            //Virheiden ilmoittaminen jos on olemassa
            View::make('/chore/show,html', array('chore' => $chore, 'errors' => $errors));
        } else {
            //Muutosten teko
            $chore->update();
            Redirect::to('/chore/' . $chore->id, array('chore' => $chore, 'message' => 'Chore has been successfully updated!'));
        }
    }

    //Merkitsee userin omistaman askareen tehdyksi
    public static function finish($id) {
        $chore = Chore::find($id);
        self::check_priviledges($chore->account_id);

        $chore->finish();

        Redirect::to('/chore', array('chore' => $chore, 'message' => 'Chore has been successfully finished!'));
    }

    //Poistaa userin omistaman askareen
    public static function delete($id) {
        $chore = Chore::find($id);
        self::check_priviledges($chore->account_id);
        
        $chore->delete();

        Redirect::to('/chore', array('message' => 'Chore has been successfully deleted!'));
    }

    //Listaa userin omistamat askareet.
    public static function index() {
        self::check_logged_in();
        $user_id = $_SESSION['user'];
        $chores = Chore::all($user_id);
        $categories = Category::all($user_id);
        $user_name = self::get_user_name();

        View::make('chore/index.html', array('chores' => $chores, 'categories' => $categories, 'user_name' => $user_name));
    }

    //Näyttää yksittäisen userin omistaman askareen.
    public static function show($id) {
        $chore = Chore::find($id);
        self::check_priviledges($chore->account_id);
        $categories = Category::all($chore->account_id);
        
        View::make('chore/show.html', array('chore' => $chore, 'categories' => $categories));
    }
    
    //Tallentaa userille uuden askareen annetuilla parametreilla
    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $attributes = Array(
            'name' => $params['name'],
            'category_id' => $params['category'],
            'priority' => $params['priority']
        );

        $chore = new Chore($attributes);
        $errors = $chore->errors();

        if (count($errors) == 0) {
            $user_id = $_SESSION['user'];
            $chore->save($user_id);
            Redirect::to('/chore/' . $chore->id, array('message' => 'Chore has been added'));
        } else {
            View::make('/chore/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }
    
    //Ohjaa userin uuden askareen luomissivulle
    public static function create() {
        self::check_logged_in();
        $categories = Category::all($_SESSION['user']);
        View::make('chore/new.html', array('categories' => $categories));
    }

}
