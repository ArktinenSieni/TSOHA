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
        $parents = Category::parentcategories($category->id);
        $children = Category::subcategories($category->id);
        
        $category_ids = array();
        
        $category_ids[] = $category->id;
        
        foreach ($children as $child) {
            $category_ids[] = $child->id;
        }
        
        $chores = Chore::categorized_all($category->account_id, $category_ids);
        
        
        View::make('category/show.html', array('category' => $category, 'categories' => $categories, 'parents' => $parents, 'children' => $children, 'chores' => $chores));
    }
    
    //Ohjaa kategotian luomissivulle
    public static function create() {
        self::check_logged_in();
        $user_id = $_SESSION['user'];
        $categories = Category::all($user_id);
        
        View::make('category/new.html', array('categories' => $categories));
    }
    
    //Tallentaa lisätyn kategorian
    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        
        $attributes = array(
            'name' => $params['name'],            
        );
        
        $parent_id = $params['parent'];
        
        $category = new Category($attributes);
        $errors = $category->errors();
        
        if (count($errors) == 0) {
            $user_id = $_SESSION['user'];
            $category->save($user_id);
            
            $category->save_connection($parent_id, $category->id);
            Redirect::to('/category', array('message' => 'Category has been added'));
        } else {
            View::make('/category/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }
    
    public static function delete($id) {
        $category = Category::find($id);
        self::check_priviledges($category->account_id);
        
        
        $categories = Category::all($category->account_id);
        if (count($categories) > 1) {
            Chore::change_category($category->id);
            $category->delete();
            Redirect::to('/category', array('message' => 'Category has been successfully deleted!'));
        } else {
            View::make('/category/' . $category->id, Array('errors' => 'You can not delete your only category'));
        }        
    }
    
    //Lisää alikategorian
    public static function add_subcategory() {
        $params = $_POST;
        
        $parent = Category::find($params['parent']);
        $child = Category::find($params['child']);
        
        self::check_priviledges($parent->account_id);
        self::check_priviledges($child->account_id);
        
        $parent->save_connection($parent->id, $child->id);
        
        Redirect::to('/category/' . $parent->id, array('message' => 'Subcategory successfully created'));
    }
    
    //Poistaa alikategorian
    public static function remove_subcategory($parent_id, $child_id) {
        $parent = Category::find($parent_id);
        $child = Category::find($child_id);
        
        self::check_priviledges($parent->account_id);
        self::check_priviledges($child->account_id);
        
        $parent->remove_connection($child_id);
        
        Redirect::to('/category/' . $parent_id, array('message' => 'Connection successfully removed'));
    }
}

