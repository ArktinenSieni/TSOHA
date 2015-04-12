<?php

class ChoreController extends BaseController {

    public static function update($id) {
        $params = $_POST;

        $attributes = Array(
            'name' => $params['name'],
            'category_id' => $params['category'],
            'priority' => $params['priority'],
            'id' => $id
        );

        $chore = new Chore($attributes);
        $errors = $chore->errors();

        if (count($errors) > 0) {
            View::make('/chore/show,html', array('chore' => $chore, 'errors' => $errors));
        } else {
            $chore->update();
            Redirect::to('/chore/' . $chore->id, array('chore' => $chore, 'message' => 'Chore has been successfully updated!'));
        }
    }

    public static function finish($id) {
        $chore = new Chore(Array('id' => $id));

        $chore->finish();

        Redirect::to('/chore', array('chore' => $chore, 'message' => 'Chore has been successfully finished!'));
    }

    public static function delete($id) {
        $chore = new Chore(Array('id' => $id));

        $chore->delete();

        Redirect::to('/chore', array('message' => 'Chore has been successfully deleted!'));
    }

    public static function index() {
        if (self::check_logged_in()) {
            $user_id = $_SESSION['user'];
            $chores = Chore::all($user_id);

            View::make('chore/index.html', array('chores' => $chores));
        }
    }

    public static function show($id) {
        if (self::check_logged_in()) {
            $chore = Chore::find($id);
            if ($chore->account_id == $_SESSION['user']) {
                View::make('chore/show.html', array('chore' => $chore));
            } else {
                Redirect::to('/', array('error' => 'You do not own this chore!'));
            }
        }
    }

    public static function store() {
        if (self::check_logged_in()) {
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
    }

    public static function create() {
        View::make('chore/new.html');
    }

}
