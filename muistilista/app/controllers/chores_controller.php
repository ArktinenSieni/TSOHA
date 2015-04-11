<?php

class ChoreController extends BaseController {

    public static function index() {
        $account_id = 1;
        $chores = Chore::all($account_id);

        View::make('chore/index.html', array('chores' => $chores));
    }

    public static function show($id) {
        $chore = Chore::find($id);

        View::make('chore/show.html', array('chore' => $chore));
    }

    public static function store() {
        $params = $_POST;

        $attributes = Array(
            'name' => $params['name'],
            'category_id' => $params['category'],
            'priority' => $params['priority']
        );

        $chore = new Chore($attributes);
        $errors = $chore->errors();
        
        if (count($errors) == 0) {
            
            $chore->save();
            Redirect::to('/chore/' . $chore->id, array('message' => 'Chore has been added'));
        }else{
            View::make('/chore/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function create() {
        View::make('chore/new.html');
    }

}
