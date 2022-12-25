<?php
// scanning undeclared classes and auto-login classes not imported
// safety method <- interesting
function classAutoLoader($class){

    $class = strtolower($class);

    $the_path = "includes/{$class}.php";
    
    // if(file_exists($the_path)){
    //     require_once($the_path);
    // } else {
    //     die("This file name {$class}.php was not found");
    // }

    if(is_file($the_path) && !class_exists(($class))){
        include $the_path;
    }
}

spl_autoload_register('classAutoLoader');