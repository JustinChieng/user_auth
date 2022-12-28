<?php

$path = ($_SERVER['REQUEST_URI']);

$path = trim ($path, '/');

$path = parse_url($path, PHP_URL_PATH);

switch($path){
    case 'login':
        require "pages/login.php";
        break;
    case 'logout':
        require "pages/logout.php";
        break;
    case 'signup':
        require "pages/signup.php";
        break;
    default:
        require "pages/home.php";
        break;
}