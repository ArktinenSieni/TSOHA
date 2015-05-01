<?php

class AccountController extends BaseController {

    // Huolehtii käyttäjistä (Accounteista), ja huolehtii sisäänkirjautimisesta,
    //eli User:in hallinnoinnista
    public static function login() {
        View::make('account/login.html');
    }

    //Sisäänkirjautuminen olemassaolevilla käyttäjätunnuksilla.
    public static function handle_login() {
        $params = $_POST;

        $user = Account::authenticate($params['name'], $params['password']);

        if (!$user) {
            View::make('account/login.html', array('error' => 'Wrong username or password', 'name' => $params['name']));
        } else {
            $_SESSION['user'] = $user->id;
            if (count(Category::all($user->id)) == 0) {
                $category = new Category(array(
                    'name' => $user->name,
                    'account_id' => $user->id
                ));
                $category->save();
            }

            Redirect::to('/', array('message' => 'Welcome back ' . $user->name . '!'));
        }
    }

    //Uloskirjautuminen, Sessionin lopettaminen eli userin poistaminen.
    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'You have been logged out!'));
    }

    public static function create() {
        View::make('account/new.html');
    }

    public static function store() {
        $params = $_POST;
        $attributes = Array(
            'name' => $params['name'],
            'password' => $params['password']
        );
        $password_verification = $params['verification'];

        $account = new Account($attributes);
        $errors = $account->errors($password_verification);

        if (count($errors) == 0) {
            $account->save();

            Redirect::to('/login', array('message' => 'Account successfully created! Please log in'));
        } else {
            View::make('/account/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

}
