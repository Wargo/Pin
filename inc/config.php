<?php
/**
 *  Config file
 */


define('PIN_CATEGORIES_SLUG', 'categorias');
define('PIN_CATEGORY_SLUG', 'categoria');
define('PIN_USER_SLUG', 'usuario');
define('PIN_USERS_SLUG', 'usuarios');
define('PIN_SINGLE_SLUG', 'pin');
define('PIN_VIA_SLUG', 'via');
define('PIN_EDIT_SLUG', 'editar');
define('PIN_FOLLOW_SLUG', 'seguir');
define('PIN_UNFOLLOW_SLUG', 'dejar-de-seguir');
define('PIN_FOLLOWING_SLUG', 'siguiendo');
define('PIN_FOLLOWERS_SLUG', 'seguidores');
define('PIN_PINS_PER_PAGE', 24);
define('PIN_BOARDS_SLUG', 'tableros');

// Images server
define('PIN_IMAGES_SERVER', 'http://static.servidordeprueba.net/');
//define('PIN_IMAGES_SERVER', 'http://arques/publi/simplecache/');
define('PIN_IMAGES_SERVER_FOLDER', PIN_IMAGES_SERVER . 'files/');
define('PIN_IMAGES_CACHE_HASH', '$1$fsdfsdfs$hiqWnj8qkFgPUj3pDJlJL.');

global $pin_slugs;
$pin_slugs = array(
		'user' => 'usuario',
		'categories' => 'categorias',
		'category' => 'categoria',
		'home' => '',
		'user' => '',
		'single' => 'pin',
		'via' => 'via'
	);