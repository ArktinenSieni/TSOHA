<?php


class CategoryController extends BaseController {
    //Kategorioiden kontrolleri
    
    //Listaa kaikki userin omistamat kategoriat.
    public static function index() {
        self::check_logged_in();
        $user_id = $_SESSION['user'];
        $categories = Category::all($user_id);
        
        
        View::make('category/index.html', array('categories' => $categories));
    }
    
    //Näyttää userin omistaman kategorian tiedot ja muokkausmahdollisuudet.
    public static function show($id) {
        //Oikeuksien tarkastaminen
        $category = Category::find($id);
        self::check_priviledges($category->account_id);
        $categories = Category::all($_SESSION['user']);
        
        View::make('category/show.html', array('category' => $category, 'categories' => $categories));
    }
}

