<?php

class BaseController {

    public static function get_user_logged_in() {
        // Katsotaan onko user-avain sessiossa
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            // Pyydetään User-mallilta käyttäjä session mukaisella id:llä
            $user = Account::find($user_id);

            return $user;
        }

        // Käyttäjä ei ole kirjautunut sisään
        return null;
    }
    
    public static function get_user_name() {
         if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            
            $user = Account::find($user_id);

            return $user->name;
        }

        
        return null;
    }
    
    public static function user_logged_in() {
        if (isset($_SESSION['user'])) {
            return true;
        }
        
        return false;
    }

    public static function check_logged_in() {
        // Toteuta kirjautumisen tarkistus tähän.
        // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('error' => 'Not logged in!'));
            
        } 
        
    }
    
    public static function check_priviledges($id) {
        self::check_logged_in();
        if ($id != $_SESSION['user']) {
            Redirect::to('/', array('error' => 'You do not have priviledges on this!'));
        }
    }

}
