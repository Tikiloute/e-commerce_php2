<?php
function register($class){
    //antislash /homepages/32/d733915466/htdocs/Controllers\MainController
    $className = str_replace('\\', '/', $class);
    require_once("$className.php");
    //transformÃ© en slash avec str_replace /homepages/32/d733915466/htdocs/Controllers/MainController
}

spl_autoload_register("register");

