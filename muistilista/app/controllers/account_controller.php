<?php

class AccountController extends BaseController{
    
  public static function login(){
      View::make('account/login.html');
  }
  
  public static function handle_login(){
    $params = $_POST;

    $user = Account::authenticate($params['name'], $params['password']);

    if(!$user){
      View::make('account/login.html', array('error' => 'Wrong username or password', 'name' => $params['name']));
    }else{
      $_SESSION['user'] = $user->id;

      Redirect::to('/', array('message' => 'Welcome back ' . $user->name . '!'));
    }
  }
}