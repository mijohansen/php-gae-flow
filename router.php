<?php

if (file_exists("../../autoload.php")) {
    require_once "../../autoload.php";
} else {
    require_once "./vendor/autoload.php";
}

use GaeFlow\Router;

return call_user_func_array(new Router(), []);

