<?php

if (file_exists("../../autoload.php")) {
    require_once "../../autoload.php";
} else {
    require_once "./vendor/autoload.php";
}

use GaeFlow\Router;

call_user_func_array(Router::class, []);

