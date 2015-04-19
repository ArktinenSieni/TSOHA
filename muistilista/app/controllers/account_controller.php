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

            Redirect::to('/', array('message' => 'Welcome back ' . $user->name . '!'));
        }
    }

    //Uloskirjautuminen, Sessionin lopettaminen eli userin poistaminen.
    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'You have been logged out!'));
    }

}
