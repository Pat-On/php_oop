<?php

// DIRECTORY_SEPARATOR - it is just slash that is different on win and linux like system
defined("ds") ? null : define("DS", DIRECTORY_SEPARATOR);


// define('SITE_ROOT', DS . 'Applications' . DS . 'XAMPP' . DS . 'xamppfiles' . DS . 'htdocs' . DS . 'gallery' );
// There is a global array in PHP called $_SERVER and the array element $_SERVER['DOCUMENT_ROOT'] 
// should get you as far as 'htdocs' or it's equivalent. So that SITE_ROOT can be shortened.
define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . DS . 'gallery');

defined('INCLUDES_PATH') ? null : define('INCLUDES_PATH', SITE_ROOT . DS . 'admin' . DS . 'includes');


// require once is more secure way that include <- check docs
require_once('new_config.php');
require_once('database.php');
require_once('db_object.php');
require_once('user.php');
require_once('photo.php');
require_once('functions.php');
require_once('session.php');
