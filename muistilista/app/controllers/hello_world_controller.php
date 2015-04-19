<?php
require 'app/models/chore.php';
class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        $categories = Category::all(1);
        
        Kint::dump($categories);
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

    public static function chores() {
        View::make('suunnitelmat/chores.html');
    }

    public static function showchore() {
        View::make('suunnitelmat/show_chore.html');
    }

    public static function showclass() {
        View::make('suunnitelmat/show_class.html');
    }

    public static function classes() {
        View::make('suunnitelmat/classes.html');
    }

    public static function account() {
        View::make('suunnitelmat/account.html');
    }

}
