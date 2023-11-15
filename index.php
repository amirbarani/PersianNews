<?php

session_start();

date_default_timezone_set('Asia/Tehran');
//Settings Web
define('CURRENT_DOMAIN', current_domain() . '/system');
define('BASE_PATH', __DIR__);
define('DISPLAY_ERROR', true);
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'project');


//Settings Emails
define('MAIL_HOST' , 'smtp.gmail.com');
define('SMTP_AUTH' , true);
define('MAIL_USERNAME' , 'dev68cms@gmail.com');
define('MAIL_PASSWORD' , 'cqdxzviibbshsilh');
define('MAIL_PORT' , 587);
define('SENDER_MAIL' , 'dev68cms@gmail.com');
define('SENDER_NAME' , 'MicroLearn');



require_once(BASE_PATH . '/database/Database.php');
require_once(BASE_PATH . '/activities/Admin/Admin.php');
require_once(BASE_PATH . '/activities/Admin/Category.php');
require_once(BASE_PATH . '/activities/Admin/Post.php');
require_once(BASE_PATH . '/activities/Admin/Banner.php');
require_once(BASE_PATH . '/activities/Admin/Comments.php');
require_once(BASE_PATH  .'/activities/Admin/Menus.php');
require_once(BASE_PATH . '/activities/Admin/User.php');
require_once(BASE_PATH . '/activities/Admin/Settings.php');
require_once(BASE_PATH . '/activities/Admin/Home.php');
require_once(BASE_PATH . '/activities/Auth/Auth.php');
require_once(BASE_PATH . '/activities/App/Home.php');




spl_autoload_register(function($class){
    $path = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    $class = str_replace('\\' , DIRECTORY_SEPARATOR , $class);
    include $path . $class . '.php';
});

function jdate($date){
    return \Parsidev\Jalali\jDate::forge($date) -> format('datetime');
}


function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
    $current_url = explode('?', current_url())[0];
    $current_url = str_replace(CURRENT_DOMAIN, '', $current_url);
    $current_url = trim($current_url, '/');
    $current_url_Array = explode('/', $current_url);
    $current_url_Array = array_filter($current_url_Array);

    $reservedUrl = trim($reservedUrl, '/');
    $reservedUrl_Array = explode('/', $reservedUrl);
    $reservedUrl_Array = array_filter($reservedUrl_Array);

    if (sizeof($current_url_Array) != sizeof($reservedUrl_Array) || methodField() != $requestMethod) {
        return false;
    }
    $parameters = [];

    for ($key = 0; $key < sizeof($current_url_Array); $key++) {
        if ($reservedUrl_Array[$key][0] == "{" && $reservedUrl_Array[$key][strlen($reservedUrl_Array[$key]) -1] == "}") {
            array_push($parameters, $current_url_Array[$key]);
        } else if ($reservedUrl_Array[$key] !== $current_url_Array[$key]) {
            return false;
        }
    }

        if (methodField() == 'POST') {
            $request = isset($_FILES) ? array_merge($_FILES, $_POST) : $_POST;
            $parameters = array_merge([$request], $parameters);
        }

        $object = new $class;
        call_user_func_array(array($object, $method), $parameters);
        exit();
}







function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}

function current_domain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}

function current_url()
{
    return current_domain() . $_SERVER['REQUEST_URI'];
}

function assets($url)
{
    $domain = trim(CURRENT_DOMAIN, '/');
    $path = $domain . '/' . trim($url, '/');
    return $path;
}

function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/');
    $path = $domain . '/' . trim($url, '/');
    return $path;
}



function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}

function display_error($display_error)
{
    if ($display_error) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_error', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}
display_error(DISPLAY_ERROR);

global $flashMessage;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

function flash($name, $value = null)
{
    if ($value === null) {
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    } else {
        $_SESSION['flash_message'][$name] = $value;
    }
}


function dd($var)
{
    echo "<pre>";
    var_dump($var);
    die();
}

//category
uri('admin/category' , 'Admin\Category' , 'index');
uri('admin/category/create' , 'Admin\Category' , 'create');
uri('admin/category/store' , 'Admin\Category' , 'store' , 'POST');
uri('admin/category/edit/{id}' , 'Admin\Category' , 'edit');
uri('admin/category/update/{id}' , 'Admin\Category' , 'update' , 'POST');
uri('admin/category/delete/{id}' , 'Admin\Category' , 'delete');

//post
uri('admin/post' , 'Admin\Post' , 'index');
uri('admin/post/create' , 'Admin\Post' , 'create');
uri('admin/post/store' , 'Admin\Post' , 'store' , 'POST');
uri('admin/post/edit/{id}' , 'Admin\Post' , 'edit');
uri('admin/post/update/{id}' , 'Admin\Post' , 'update' , 'POST');
uri('admin/post/delete/{id}' , 'Admin\Post' , 'delete');
uri('admin/post/breaking_news/{id}' , 'Admin\Post' , 'breaking_news');
uri('admin/post/selected/{id}' , 'Admin\Post' , 'selected');

//banners
uri('admin/banners' , 'Admin\Banners' , 'index');
uri('admin/banners/create' , 'Admin\Banners' , 'create');
uri('admin/banners/store' , 'Admin\Banners' , 'store' , 'POST');
uri('admin/banners/edit/{id}' , 'Admin\Banners' , 'edit');
uri('admin/banners/update/{id}' , 'Admin\Banners' , 'update' , 'POST');
uri('admin/banners/delete/{id}' , 'Admin\Banners' , 'delete');

//comments
uri('admin/comments' , 'Admin\Comments' , 'index');
uri('admin/comments/change/{id}' , 'Admin\Comments' , 'change');

//Menus
uri('admin/menus' , 'Admin\Menus' , 'index');
uri('admin/menus/create' , 'Admin\Menus' , 'create');
uri('admin/menus/store' , 'Admin\Menus' , 'store' , 'POST');
uri('admin/menus/edit/{id}' , 'Admin\Menus' , 'edit');
uri('admin/menus/update/{id}' , 'Admin\Menus' , 'update' , 'POST');
uri('admin/menus/delete/{id}' , 'Admin\Menus' , 'delete');

//users
uri('admin/users' , 'Admin\User' , 'index');
uri('admin/users/permission/{id}' , 'Admin\User' , 'permission');
uri('admin/users/edit/{id}' , 'Admin\User' , 'edit');
uri('admin/users/update/{id}' , 'Admin\User' , 'update' , 'POST');
uri('admin/users/delete/{id}' , 'Admin\USer' , 'delete');

//web settings
uri('admin/settings' , 'Admin\Settings' , 'index');
uri('admin/settings/edit/' , 'Admin\Settings' , 'edit');
uri('admin/settings/update/' , 'Admin\Settings' , 'update' , 'POST');

//panel admin
uri('admin' , 'Admin\Dashboard' , 'index');

//registers
uri('register' , 'Auth\Auth' , 'register');
uri('register/store' , 'Auth\Auth' , 'registerstore' , 'POST');

//activation accounts
uri('activation/{verify_token}' , 'Auth\Auth' , 'activation');

//login
uri('login' , 'Auth\Auth' , 'login');
uri('check-login' , 'Auth\Auth' , 'checkLogin' , 'POST');

//logout
uri('logout' , 'Auth\Auth' , 'logout');

//forgot password
uri('forgot' , 'Auth\Auth' , 'forgot');
uri('forgot/request' , 'Auth\Auth' , 'forgotRequest' , 'POST');
uri('reset-password-form/{forgot_token}' , 'Auth\Auth' , 'resetPasswordView');
uri('reset-password-store/{forgot_token}' , 'Auth\Auth' , 'resetPassword' , 'POST');

//Home
uri('/' , 'App\Home' , 'index');
uri('/home' , 'App\Home' , 'index');
uri('/show-post/{id}', 'App\Home', 'show');
uri('/show-category/{id}', 'App\Home', 'category');
uri('/comment-store/{post_id}', 'App\Home', 'commentStore', 'POST');



echo '404 - page not found';


