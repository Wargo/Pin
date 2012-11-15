<?php
/*
Plugin Name: Pin
Description: Captura imágenes
Version: 1.0
Author: Guille
Author URI: http://www.artvisual.net
*/

//error_reporting(E_ALL);
//ini_set('display_errors', '1');


require_once('inc/config.php');
require_once('inc/functions.php');
require_once('inc/ajax.php');

define('PIN_PLUGIN_URL', WP_PLUGIN_URL . '/pin/');
define('PIN_PLUGIN_DIR', WP_PLUGIN_DIR . '/pin/');
define('PIN_PLUGIN_AJAX', PIN_PLUGIN_URL . 'inc/process.php');

class Pin {

	var $logo = '';
	var $table_name = 'pin';
	var $db_version = 1.0;
	var $template_notices = array();
	
	function __construct() {
		register_activation_hook(__FILE__, array(&$this, 'install')); // instala BBDD sólo se ejecuta cuando se activa el plugin
		if ($logo = get_option('pin_logo')) {
			$this->logo = $logo;
		}
		//add_action('the_content', array(&$this, 'show')); // Añade al contenido de la página la función show()
		//add_action('wp_print_styles', array(&$this, 'pin_style')); // Carga estilos CSS
		if ( is_multisite() ) {
			add_action('network_admin_menu', array(&$this, 'menu')); // Añade al menú del administrador la función menu()
		} else {
			add_action('admin_menu', array(&$this, 'menu')); // Añade al menú del administrador la función menu()
		}
		
		add_action("template_redirect", array(&$this, 'template_redirect')); // Redirige al los templates de /tpl
		add_action('init', array(&$this, 'rewrite_rules'));
		// hook add_query_vars function into query_vars
		add_filter('query_vars', array(&$this, 'add_query_vars'));
		
		add_action('wp_enqueue_scripts', array( &$this, 'pin_button_js'));
		//add_action( 'wp_head', array( &$this, 'head_script'));
		add_action('wp_head', array(&$this, 'add_header_script'));
		
		add_action( 'pin_template_notices' , array( &$this, 'template_notices'));

		add_filter('wp_title', array( &$this, 'wp_title'), 80, 3);

	}

	function debug($array) {
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
	
	function pin_button_js () {
		if ( is_singular()) {
			wp_enqueue_script( 'pin_button', PIN_PLUGIN_URL . 'inc/js/pin-button.js', array('jquery'));
		}
	}

	function getCategory($category_slug) {
		$categories = $this->getCategories();
		$aux = array();
		foreach($categories as $category) {
			extract((array)$category);
			$aux[$slug] = $name;
		}
		return $aux[$category_slug];
	}

	public function getCategories() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		return $wpdb->get_results("SELECT * FROM " . $table_name . "_categories ORDER BY name");
	}
	/**
	 * Returns indexed categories array
	 *
	 * @return array
	 */
	
	public function get_categories_array( $hide_empty = false ) {
	
	/**
	SELECT COUNT(*) as count_boards, wp_pin_categories.*
	FROM wp_pin_boards, wp_pin_categories
	WHERE 
	wp_pin_categories.id = wp_pin_boards.category
	GROUP BY category
	ORDER BY count_boards DESC
	 */
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		if ( $hide_empty )
			$where = "WHERE pin_count > 0";
		else 
			$where = "";
			
		$cats = $wpdb->get_results("SELECT * FROM " . $table_name . "_categories cat $where ORDER BY pin_count DESC");
		$num_cats = count($cats);
		
		for ( $i=0; $i<$num_cats; $i++) {
			$return[$cats[$i]->slug] = $cats[$i];
		}
		
		return $return;
		
	}
	/**
	 * Returns categories array and last 5 pins
	 *
	 * @return unknown
	 */
	public function get_categories_and_pins() {
	
		global $wpdb, $categories;
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		$categories = $wpdb->get_results("SELECT * FROM " . $table_name . "_categories");
		$count_categories = count($categories);
		
		for ( $i=0; $i< $count_categories; $i++ ) {
			$boards = $wpdb->get_col("select id from wp_pin_boards where category = {$categories[$i]->id}");
			if ( ! empty( $boards)) {
				$boards_string =  implode(',', $boards);
				$categories[$i]->pins = $wpdb->get_col( "SELECT url FROM $table_name where board in ($boards_string) order by time DESC LIMIT 5");
			}
			
		}
		
		return ( $categories );
		
	}
	
	function defaultCategories() {
	
		$default = array(
				'Decoración infantil',
				'Dibujos',
				'Moda y complementos',
				'Juegos y juguetes',
				'Manualidades',
				'Lugares mágicos',
				'Fiestas y celebraciones',
				'Disfraces',
				'Habitación del bebé',
				'Fotografía infantil',
				'Mascotas',
				'Recetas originales',
				'Alimentación y comida',
				'Póster infantil',
				'Accesorios para tu bebé',
				'Deporte',
				'Momentos felices',
				'Hogar dulce hogar',
				'Vacaciones divertidas',
				'Libros y cuentos',
				'Películas',
				'Espectáculos para niños',
				'Amor en familia',
				'Humor',
				'Haciendo travesuras',
				'Sorprendente',
				'Regalos',
				'Su habitación',
				'Bebés de famosos',
				'¡Qué monada!',
				'Cuidando del bebé'
				
			);
		sort($default);	
		$return_array = array();
		
		foreach ( $default as $def_cat ) {
			array_push( $return_array, array('name' => $def_cat, 'slug' => sanitize_title( $def_cat ) ));	
		}
		
		return $return_array;
	}

	function logo() {
		echo '<img class="logo" src="' . $this->logo . '" />';
	}

	function install() {
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$sql_1 = "
			CREATE TABLE IF NOT EXISTS " . $table_name . "_comments (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				pin_id mediumint(9) NOT NULL,
				user_id  mediumint(9) NOT NULL,
				content text,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY id (id)
			);";
		dbDelta($sql_1);
		
		$sql_2 = "	
			CREATE TABLE IF NOT EXISTS " . $table_name . "_categories (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				slug varchar(255) NOT NULL,
				pin_count int(11) DEFAULT 0 NOT NULL,
				UNIQUE KEY slug (slug),
				PRIMARY KEY id (id)
			);";
		dbDelta($sql_2);
		
		$sql_3 = "
			CREATE TABLE IF NOT EXISTS $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				user_id bigint(20) NOT NULL,
				user_name varchar(255),
				url varchar(255) NOT NULL,
				name varchar(255) NOT NULL,
				category varchar(255) NOT NULL,
				board mediumint(9),
				via varchar(255) NULL,
				referer varchar(255) NULL,
				parent mediumint(9) DEFAULT 0 NOT NULL,
				repins mediumint(9) DEFAULT 0 NOT NULL,
				comments_count mediumint(9) DEFAULT 0 NOT NULL,
				board mediumint(9),
				PRIMARY KEY id (id)
			);";
		dbDelta($sql_3);
		
		$sql_4 = "	
			CREATE TABLE IF NOT EXISTS " . $table_name . "_boards (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				slug varchar(255) NOT NULL,
				user_id  mediumint(9) NOT NULL,
				category mediumint(9),
				description text,
				pin_count int(11) DEFAULT 0 NOT NULL,
				followers int(11) DEFAULT 0 NOT NULL,
				PRIMARY KEY id (id)
			);";
		dbDelta($sql_4);
		
		
		
		$sql_5 = "	
			CREATE TABLE IF NOT EXISTS " . $table_name . "_follow (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				follower varchar(255) NOT NULL,
				followed varchar(255) NOT NULL,
				board  mediumint(9) NOT NULL,
				PRIMARY KEY id (id)
			);";
		dbDelta($sql_5);
		
		$sql = "";
		$categories = $this->defaultCategories();
		foreach($categories as $category) {
			$sql .= 'INSERT INTO ' . $table_name . '_categories (name, slug) values (\'' . $category['name'] . '\', \'' . $category['slug'] . '\'); ';
		}
		
		//wp_mail( 'j.arques@artvisual.net', 'sql', $sql );
		
		
		//$wpdb->query( $sql );
		dbDelta($sql);

		add_option('pin_db_version', $this->db_version);
		
		$this->rewrite_rules();
		flush_rewrite_rules();
	}

	
	function pin_style () {
		global $post;
		if ($post_id = get_option('pin_post_id')) {
			if ($post->ID == $post_id) {
				if (is_user_logged_in()) {
					wp_enqueue_style('pin_style', '/wp-content/plugins/pin/style.css');
				}
			}
		}
	}


	function show() {
		global $post;
		if ($post_id = get_option('pin_post_id')) {
			if ($post->ID == $post_id) {
				if (is_user_logged_in()) {
					global $wpdb, $user_ID;
					$table_name = $wpdb->base_prefix . $this->table_name;
					$query = "SELECT * FROM $table_name WHERE user_id = '$user_ID'";
					$pins = $wpdb->get_results($query);
					echo '<div class="show_pins clearfix">';
						foreach ($pins as $p) {
							extract((array)$p);
							echo '
							<div class="img">
								<img src="' . $url . '" />
								<br />
								<a href="' . $url . '" target="_blank">' . $name . '</a>
								<br />' . 
								$this->getCategory($category) . '
							</div>
							';
						}
					echo '</div>';
				}
			}
		}
	}
	

	function menu() {
		add_menu_page( 'Cosas bonitas', 'Cosas bonitas', 'manage_options', 'pin-options', array(&$this, 'options_page'), PIN_PLUGIN_URL . 'inc/img/smiley_smile.png');
		add_submenu_page( 'pin-options', 'Opciones', 'Opciones', 'manage_options', 'pin-options', array(&$this, 'options_page'));
		$page = add_submenu_page( 'pin-options', 'Categorías', 'Categorías', 'manage_options', 'pin-categories', array(&$this, 'categories_page'));
		
	}
	
	function categories_page () {
	
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		if ( ! empty($_POST['cat-name']) ) {
		
			global $wpdb, $use_ID, $wp_pin;
			$table_name = $wpdb->base_prefix . $wp_pin->table_name . '_categories';
			
			$slug = $_POST['cat-slug'];
			
			if ( empty($_POST['cat-slug'])) {
				$slug = sanitize_title( $_POST['cat-name']);
			}
			$ok = $wpdb->insert( $table_name, array('name' => $_POST['cat-name'], 'slug' => $slug ));
			if ( $ok )
				echo '<div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Categoría añadida</strong></p></div>';
			else 
				echo '<div class="error settings-error" id="setting-error-settings_updated"><p><strong>Error guardando la categoría</strong></p></div>';

		}
		//unset($_POST);
		//unset($_REQUEST);
		
		//Belleza     Cuidado de niños     Curiosidades     Decoración     Famosos     Fotografía     Moda     Ocio
		
		$cats = $this->getCategories();
		
		?>
		<script type="text/javascript" src="<?php echo PIN_PLUGIN_URL ?>/inc/js/admin.js"></script>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>Categorías</h2>
			<div id="col-container">
				
			
				<div id="col-left" style="float: left;"><div class="col-wrap">
					<div class="form-wrap">
					<h3>Añadir nueva categoría</h3>
					<form action="" method="post" id="addcat">

					<div class="form-field form-required">
						<label for="cat-name">Nombre</label>
						<input type="text" aria-required="true" size="40" value="" id="cat-name" name="cat-name">
						<p>El nombre es cómo aparecerá en tu sitio.</p>
					</div>
					
					<div class="form-field">
						<label for="cat-slug">Slug</label>
						<input type="text" size="40" value="" id="cat-slug" name="cat-slug">
						<p>El “slug” es la versión amigable de la URL del nombre. Suele estar en minúsculas y contiene sólo letras, números y guiones.</p>
					</div>

					<p class="submit"><input type="submit" value="Añadir nueva categoría" class="button" id="submit" name="submit"></p></form></div>
					
				</div></div>
			<?php
			if (! empty($cats)) {
				?>
				<div id="col-right"><div class="col-wrap">
				
			<table cellspacing="0" class="wp-list-table widefat fixed tags" id="pin-categories">
				<thead>
				<tr>
					<th width="33%">Nombre</th>
					<th width="33%">Slug</th>
					<th width="10%">Pines</th>
					<th></th>
				</tr>
				</thead>
				
				<tbody>
				<?php
				
				foreach ($cats as $cat) {
					echo "<tr cat-id='{$cat->id}'>
							<td class='name'><span>{$cat->name}</span></td>
							<td class='slug'><span>{$cat->slug}</span></td>
							<td class='count'>{$cat->pin_count}</td>
							<td class='actions'><span><a href='#' class='edit-cat' cat-id='{$cat->id}'>Editar</a> | <a href='#' class='delete-cat' cat-id='{$cat->id}'>Eliminar</a></span></td>
						  </tr>";
				}
				?>
				</tbody>
				
				</table></div></div>
				
				<?php
				
			}
		echo '</div></div>';
	}

	function options_page() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		if (!empty($_POST['post_id'])) {
			$post_id = $_POST['post_id'];

			update_option('pin_post_id', $post_id);

			if (!empty($_FILES['logo']['size'])) {
				$logo = wp_handle_upload($_FILES['logo']);
				update_option('pin_logo', $logo['url']);
			}
			flush_rewrite_rules();
		}
		
		if ( ! empty( $_POST['twitter'])) {
		
			update_option('pin_twitter', $_POST['twitter']);
			
		}

		global $wpdb;

		$query = "SELECT id, post_title FROM " . $wpdb->base_prefix . "posts WHERE post_type = 'page'";
		$pages = $wpdb->get_results($query);

		$current = get_option('pin_post_id');
		$twitter = get_option('pin_twitter');
		
		if ( empty( $twitter ))
			$twitter = '';
		
		echo '
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>' . __('Ajustes', true) . '</h2>
			<h3>' . __('Ajustes para el plugin Pin', true) . '</h3>
			<form action="" method="post" enctype="multipart/form-data">
				<div>
					<label for="logo">' . __('Adjunta un logotipo para los popup\'s', true) . '</label>
					<input type="file" name="logo" id="logo" />';
					if ($img = get_option('pin_logo')) {
						echo '<br /><img src="' . $img . '" style="max-width: 200px;" />';
					}
					wp_nonce_field('upload_pin_logo');
					echo '
					<input type="hidden" name="action" value="wp_handle_upload" />
				</div>
				
				<div>
					<label for="post_id">' . __('Selecciona la página Pin', true) . '</label>
					<select name="post_id" id="post_id">';
						foreach ($pages as $page) {
							extract((array)$page);
							if ($current == $id) {
								echo '<option selected="selected" value="' . $id . '">' . $post_title . '</option>';
							} else {
								echo '<option value="' . $id . '">' . $post_title . '</option>';
							}
						}
					echo '</select>
					
				<h3>Redes Sociales</h3>
				<div>
					<label for="twitter">Cuenta de Twitter</label>
					<input type="text" name="twitter" id="twitter" value="' . $twitter .'"/>
					<p class="decription">Esta cuenta será usada para el <code>vía</code> cuando se comparta un tweet</p>
				</div>
				
				<p class="submit"><input type="submit" value="Guardar cambios" class="button-primary" id="submit" name="submit"></p>
			</form>
		</div>
		';
	}
	/**
	 * Se hace la redircción al template correspondiente y se cargan los datos necesarios
	 *
	 */
	function template_redirect() {
	
	    global $wp, $wpdb, $wp_query, $pins, $pin_categories;
	    $plugindir = dirname( __FILE__ );
	    
	    
	    $pageid = get_option('pin_post_id');
	    $pagename = $wpdb->get_var ("SELECT post_name FROM wp_posts WHERE ID = $pageid");
	    
	    // DEBUG
	    //dump($wp->query_vars);
	    
	    
	    //A Specific Custom Post Type
	    if ( ! empty($wp->query_vars["pagename"]) && $wp->query_vars["pagename"] == $pagename) {
	    
		
	    	
			// Report simple running errors
			//error_reporting(E_ALL);ini_set('display_errors', '1');		
			//error_reporting(E_ERROR | E_WARNING | E_PARSE);
			
			
			
	    	global $pins, $pin, $pin_categories, $next;
	    	
	    	$pinsection = (get_query_var('pinsection')) ? get_query_var('pinsection') : '';  		
	    
	    	$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
	    	$next = false;
	    	
	    	switch ( $pinsection ) {
	    	
				/* CATEGORIES
				--------------------------------------------------------------------------------------- */
	    		case PIN_CATEGORIES_SLUG:
	    		
	    			$pin_categories = $this->get_categories_and_pins( );
    				$templatefilename = 'categories.php';	
	    			break;
	    			
				/* CATEGORY
				--------------------------------------------------------------------------------------- */
	    		case PIN_CATEGORY_SLUG:
	    			$pin_categories = $this->get_categories_array();
	    			
	    			//$categories[$i]->pins = $wpdb->get_col( "SELECT url FROM $table_name where board in ($boards_string) order by time DESC LIMIT 5");
	    			$cat_slug = get_query_var('pinid');
	    			
	    			$table_name = $wpdb->base_prefix . $this->table_name;
	    			$categories_table = $table_name . '_categories';
	    			
	    			$q="SELECT id FROM $categories_table WHERE slug = '{$cat_slug}'";
	    			
	    			//dump($q);
	    			$cat_id = $wpdb->get_var($q);	
	    			//dump($cat_id);
	    			$boards_in_cat = $wpdb->get_col("SELECT id FROM {$wpdb->base_prefix}{$this->table_name}_boards WHERE category = $cat_id");
	    			//dump($boards_in_cat);
	    			
	    			if ( ! empty( $boards_in_cat )) {
	    				$pins = $this->get_pins( array('board' => $boards_in_cat), PIN_PINS_PER_PAGE, $page);
	    			}

    				if ( count($pins) == PIN_PINS_PER_PAGE ) {
		    			$has_more = $this->get_pins(array('board' => $boards_in_cat), 1, $page+1);
		    			if ( $has_more ) {
		    				$next = pin_url( 'category', get_query_var('pinid'), $page + 1);
		    			}
    				}
	
    				
    				$templatefilename = 'category.php';	
	    			break;
	    			
				/* PIN
				--------------------------------------------------------------------------------------- */
	    		case PIN_SINGLE_SLUG:
	    			global $board, $pin_user;
	    			$pin_categories = $this->get_categories_array();
	    			$pin = $this->get_pin( get_query_var('pinid') );
	    			$pin_user = get_userdata( $pin->user_id );
	    			$board = $this->get_board( null, null, $pin->board );
	    			
	    			$edit = get_query_var('edit');
	    			
	    			if ($edit) {
	    				
	    				if ( pin_current_user_can_edit( get_query_var('pinid') )) {
	    					if ( ! empty( $_POST['pin_id'] ))
	    						$this->save();
	    					$pin = $this->get_pin( get_query_var('pinid') );
	    					wp_enqueue_script( 'pin-popup', PIN_PLUGIN_URL . 'popup.js', array('jquery'));
							wp_enqueue_script( 'pin-edit', WP_PLUGIN_URL . '/pin/inc/js/edit-pin.js', array('jquery'));
							wp_enqueue_script( 'pin-user', WP_PLUGIN_URL . '/pin/inc/js/user.js', array('jquery'));
	    					$templatefilename = 'edit.php';	
	    				} else {
	    					wp_redirect( pin_url( 'single', get_query_var('pinid')));
	    				}
	    			} else {
	    				add_action('wp_head', array(&$this, 'metadata'));
	     				wp_enqueue_script( 'pin-single', WP_PLUGIN_URL . '/pin/inc/js/single.js', array('jquery'));
	     				wp_enqueue_script( 'underscore', WP_PLUGIN_URL . '/pin/inc/js/underscore-min.js', array('jquery'));
	    				$templatefilename = 'single.php';	
	    			}
    				
    				
	    			break;
	    			
				/* USER
				--------------------------------------------------------------------------------------- */
	    		case PIN_USER_SLUG:
	    		
	    			global $pin_user, $boards;
	    			$pin_user = get_user_by('login', get_query_var('pinid'));
	    			$templatefilename = 'user.php';
	    			
	    			if ( is_user_logged_in())
	    				wp_enqueue_script( 'pin-user', WP_PLUGIN_URL . '/pin/inc/js/user.js', array('jquery'));
	    			
	    			$pin_categories = $this->get_categories_array();
	    			
	    			if ( ! empty( $_POST['action'] ) && pin_is_my_profile() ) {
	    				if ( $_POST['action'] == 'new-board' ) {
	    					//dump($_POST);
	    					$return = $this->add_board( $_POST['board-name'], $_POST['board-category'], $_POST['board-description']);
	    					
	    					if ( isset($_POST['pin_id'])) {
	    						$this->update_pin( $_POST['pin_id'], array('board' => $return['id']));
	    						wp_redirect( pin_url( 'single', $_POST['pin_id']) . '/edit');
	    					}
	    					
	    					wp_redirect( pin_url( 'user', $pin_user->data->user_login, 1, $return['slug']));
	    					die();
		    			} else{
		    				//dump($_POST);
	    					$return = $this->add_board( $_POST['board-name'], $_POST['board-category'], $_POST['board-description'], $pin_user->ID, $_POST['board-id']);
	    					wp_redirect( pin_url( 'user', $pin_user->data->user_login, 1, $return['slug']));
	    					die();
		    			}
	    			}

	    			
	    			if ( get_query_var('pinsubsection') === 'pins') { 
	    			
						// USER PINS
						
	    				$pins = $this->get_pins( array('user_name' => get_query_var('pinid')), PIN_PINS_PER_PAGE, $page);
	    				
	    				if ( count($pins) == PIN_PINS_PER_PAGE ) {
			    			$has_more = $this->get_pins(array('user_name' => get_query_var('pinid')), 1, $page+1);
			    			if ( $has_more ) {
			    				$next = pin_url( 'user', get_query_var('pinid') . '/pins', $page + 1);
			    			}
	    				}
	    			
	    			} elseif ( get_query_var('pinsubsection') == PIN_FOLLOWING_SLUG ){
	    			
	    				// FOLLOWING
	    				
	    				global $users;
	    				$boards = $this->get_following($pin_user->ID);
	    				
	    			} elseif ( get_query_var('pinsubsection') == PIN_BOARDS_SLUG ){
	    			
	    				// USER BOARDS
	    				
	    				$boards = $this->get_boards( $pin_user->ID, 5);
	    				
	    			} elseif ( get_query_var('pinsubsection') == PIN_FOLLOWERS_SLUG ){
	    				
	    				// FOLLOWERS
	    				
	    				global $users;
	    				$users = $this->get_followers($pin_user->ID);
	    				
	    			} elseif ( get_query_var('pinsubsection') ) {
	    				
    					// SINGLE BOARD
    					
    					global $board;
    					$board = $this->get_board( $pin_user->ID, get_query_var('pinsubsection'));
    					
    					if ( get_query_var('edit') && pin_is_my_profile()) {
    						// Editar un tablero
    						
    						$templatefilename = 'edit-board.php';
    					} else {
    						// Loop de un tablero
    						$pins = $this->get_pins( array('board' => $board->id), PIN_PINS_PER_PAGE, $page);
    						
		    				if ( count($pins) == PIN_PINS_PER_PAGE ) {
				    			$has_more = $this->get_pins( array('board' => $board->id), 1, $page+1);
				    			if ( $has_more ) {
				    				$next = pin_url( 'user', get_query_var('pinid'), $page + 1, get_query_var('pinsubsection'));
				    			}
		    				}
		    				
    						$templatefilename = 'board.php';
    					}
						
	    			} else {
	    			
	    				// USER HOME
	    			
    					$follow_table = $wpdb->base_prefix . $this->table_name . '_follow';
	    				$include = $wpdb->get_col("SELECT board FROM $follow_table WHERE follower = $pin_user->ID");
	    				
	    				if ( empty($include)) {
	    					$or = "AND {$wpdb->base_prefix}{$this->table_name}.user_id = $pin_user->ID";
	    				} else {
	    					$or = "OR {$wpdb->base_prefix}{$this->table_name}.user_id = $pin_user->ID";
	    				}
	    			
						
						
	    				
						$pins = $this->get_pins( array('board' => $include), PIN_PINS_PER_PAGE, $page, $or);
						
						
	    				if ( count($pins) == PIN_PINS_PER_PAGE ) {
			    			$has_more = $this->get_pins( array('board' => $include), 1, $page+1, $or);
			    			if ( $has_more ) {
			    				$next = pin_url( 'user', get_query_var('pinid'), $page + 1);
			    			}
	    				}
						
						
	    				
	    			}
	    			
    				
    				
	    			break;
	    			
	    		case PIN_VIA_SLUG:
	    		
				/* VÍA
				--------------------------------------------------------------------------------------- */
	    			$pin_categories = $this->get_categories_array();
	    			$pins = $this->get_pins( array('via' => get_query_var('pinid')), PIN_PINS_PER_PAGE, $page);
	    			
	    			if ( empty($pins)) {
	    				$wp_query->is_404 = true;
	    			} else {
	    				if ( count($pins) == PIN_PINS_PER_PAGE ) {
			    			$has_more = $this->get_pins(array('via' => get_query_var('pinid')), 1, $page+1);
			    			if ( $has_more ) {
			    				$next = pin_url( 'via', get_query_var('pinid'), $page + 1);
			    			}
	    				}

	    			}


    				$templatefilename = 'via.php';	
    				
	    			break;
	    			
	    		default:
	    		
				/* HOME
				--------------------------------------------------------------------------------------- */
				
	    		
	    			//$pin_categories = $this->get_categories_array( );
	    			
	    			$pins = $this->get_pins( null, PIN_PINS_PER_PAGE, $page);
	    			
    				if ( count($pins) == PIN_PINS_PER_PAGE ) {
		    			$has_more = $this->get_pins( null, 1, $page+1);
		    			if ( $has_more ) {
		    				$next = pin_url( null , null, $page + 1);
		    			}
    				}
    				
    				

	    			$templatefilename = 'home.php';	
	    	}
	    		

	    	
	        
	        if (file_exists(TEMPLATEPATH . '/pin/' . $templatefilename)) {
	            $return_template = TEMPLATEPATH . '/pin/' . $templatefilename;
	        } else {
	            $return_template = $plugindir . '/templates/pin/' . $templatefilename;
	        }
	        
	        
	        
	        wp_deregister_script('jquery-carousel');
	        wp_enqueue_script( 'pin-common', WP_PLUGIN_URL . '/pin/inc/js/common.js', array('jquery'));
	        wp_enqueue_script( 'pin-bootstrap', WP_PLUGIN_URL . '/pin/inc/js/bootstrap.min.js', array('jquery'));
	     	wp_enqueue_style('pin', WP_PLUGIN_URL . '/pin/inc/css/styles.css');	
	     	
	     	if ( $pinsection != PIN_SINGLE_SLUG ) {
		     	wp_enqueue_script( 'jquery-infinitescrol', WP_PLUGIN_URL . '/pin/inc/js/jquery.infinitescroll.js', array('jquery'));	     	     	
		     	wp_enqueue_script( 'jquery-masonry', WP_PLUGIN_URL . '/pin/inc/js/jquery.masonry.min.js', array('jquery'));	     	
		     	wp_enqueue_script( 'pin-loop', WP_PLUGIN_URL . '/pin/inc/js/loop.js', array( 'jquery-infinitescrol' ));
	     	}

		    if ( ! $wp_query->is_404 ) {
		    	
		        include($return_template);
		        die();
		    } else {
		        $wp_query->is_404 = true;
		    }
		    
	    }
	}
	
	function metadata () {
		global $pin, $board;
		
		//$imagesize = getimagesize($pin->url);
		$size = 550;
		
		/*			
		if ( $imagesize[0] < $size )
			$size = $imagesize[0];
		*/
		if (! empty($board->name))
			$title = $board->name;
		else 
			$title = $pin->name;
		?>
		<!-- Facebook Open Graph 	-->
		<meta property="og:url" content="<?php echo pin_url('single', $pin->id)?>"/>  
		<meta property="og:title" content="<?php echo trim($title) ?>" />  
		<meta property="og:type" content="article" />  
		<meta property="og:description" content="<?php echo trim($pin->name) ?>" /> 
		
		<!-- Google Schema.org -->
		<meta itemprop="name" content="<?php echo trim($title) ?>"/>
		<meta property="url" content="<?php echo pin_url('single', $pin->id)?>"/> 
		<meta itemprop="description" content="<?php echo trim($pin->name) ?>"/>
		
		
		<!-- Open Graph & Schema.org images	-->

		<?php /*

		<link rel="image_src" href="<?php echo site_url(); thumbGen( $pin->url, $size, 0, "background=transparent"); ?>" />
		<meta property="og:image" content="<?php echo site_url(); thumbGen( $pin->url, $size, 0, "background=transparent"); ?>" />
		<meta itemprop="image" content="<?php echo site_url(); thumbGen( $pin->url, $size, 0, "background=transparent"); ?>">
		*/ ?>


		<?php
	}
	
	function wp_title ( $title, $sep, $seplocation ) {
	
		if (! is_pin())
			return $title;
			
		global $pins, $pin, $pin_categories, $next;
		
		$pinsection = (get_query_var('pinsection')) ? get_query_var('pinsection') : '';  		
	    $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
	    
	    $pinpage = get_the_title() . ' | ' . get_bloginfo('name');
	    $title = $pinpage; 
	    	
    	switch ( $pinsection ) {
    		case PIN_CATEGORIES_SLUG:
    			$title = __('Categorías', 'pin') . " - $pinpage";
    			break;
    		case PIN_CATEGORY_SLUG:
    			$title = "{$pin_categories[get_query_var('pinid')]->name} - $pinpage";
    			break;
    		case PIN_SINGLE_SLUG:
    			$title = "{$pin->name} - $pinpage";
    			break;
    		case PIN_USER_SLUG:
				$user = get_user_by('login', get_query_var('pinid'));
				$title = "{$user->display_name} - $pinpage";
    			break;
    		case PIN_VIA_SLUG:
				$title = __('Vía') .' '. get_query_var('pinid') . " - $pinpage";
    			break;
    	}
    	return $title;
	}
	
	function save(){
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		if ( empty($_POST) || !wp_verify_nonce($_POST['_wpnonce'],'edit-pin') ){
		   print 'Sorry, your nonce did not verify.';
		   exit;
		}else{
	   		if ($wpdb->update( 	$table_name , array( 'name' => $_POST['pin_description'], 'board' => $_POST['board']), array('id' => $_POST['pin_id']))){
	   			// Actualizamos el contador de categorías
	   			//pin_update_cat_pins
				pin_update_board_pins( $_POST['pin_board'] );
	   			wp_redirect( pin_url('single', $_POST['pin_id']));
	   			$this->add_template_notice( __('Cambios guardados correctamente'));
	   		} else {
	   			//pin_update_cat_pins( $_POST['pin_category'] );
	   			pin_update_board_pins( $_POST['pin_board'] );
	   			wp_redirect( pin_url('single', $_POST['pin_id']));
	   			$this->add_template_notice ( __('Ha habido un error guardando los datos'), 'error');
	   		}
		}

	
	}
	
	function update_pin( $pin_id, $args ) {
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		$wpdb->update($table_name, $args, array('id' => $pin_id));
	}
	
	function rewrite_rules () {
	
		global $wp, $wpdb;
		$pageid = get_option('pin_post_id');
		$pagename = $wpdb->get_var ("SELECT post_name FROM wp_posts WHERE ID = $pageid");

		// Categories archive
	
		add_rewrite_rule(
			"^$pagename/" . PIN_CATEGORIES_SLUG ."?/?$",
			'index.php?pagename=' . $pagename . '&pinsection=' . PIN_CATEGORIES_SLUG,
			'top'
		);
		// Single category
		add_rewrite_rule(
			"^$pagename/" . PIN_CATEGORY_SLUG ."/([^/]*)?/?$",
			'index.php?pagename=' . $pagename . '&pinsection=' . PIN_CATEGORY_SLUG . '&pinid=$matches[1]',
			'top'
		);
		
		// Vía
		add_rewrite_rule(
			"^$pagename/" . PIN_VIA_SLUG ."/([^/]*)(/page/([0-9]+)?)?$",
			'index.php?pagename=' . $pagename . '&pinsection=' . PIN_VIA_SLUG . '&pinid=$matches[1]',
			'top'
		);
		
		// Archive
		add_rewrite_rule(
			"^$pagename(/page/([0-9]+)?)?/?$",
			'index.php?pagename=' . $pagename . '&paged=$matches[2]',
			'top'
		);
		
		
		// Single user
		add_rewrite_rule(
			"^$pagename/([^/]*)(/page/([0-9]+)?)?$",
			'index.php?pagename=' . $pagename . '&pinsection=' . PIN_USER_SLUG .'&pinid=$matches[1]&paged=$matches[3]',
			'top'
		);
		
		
		// Single user pins
		add_rewrite_rule(
			"^$pagename/([^/]*)/pins(/page/([0-9]+)?)?$",
			'index.php?pagename=' . $pagename . '&pinsection=usuario&pinsubsection=pins&pinid=$matches[1]&paged=$matches[3]',
			'top'
		);
		
		// Single pin
		add_rewrite_rule(
			"^$pagename/pin/([0-9]+)/?$",
			'index.php?pagename=' . $pagename . '&pinsection=pin&pinid=$matches[1]',
			'top');
			
		// Edit pin
		add_rewrite_rule(
			"^$pagename/pin/([0-9]+)/".PIN_EDIT_SLUG."?",
			'index.php?pagename=' . $pagename . '&pinsection=pin&pinid=$matches[1]&edit=1',
			'top');
		// Edit Board
		add_rewrite_rule(
			"^$pagename/([^/]*)/([^/]*)/".PIN_EDIT_SLUG."?",
			'index.php?pagename=' . $pagename . '&pinsection=' . PIN_USER_SLUG . '&pinid=$matches[1]&pinsubsection=$matches[2]&edit=1',
			'top');
		// Board
		add_rewrite_rule(
			"^$pagename/([^/]*)/([^/]*)(/page/([0-9]+)?)?$",
			'index.php?pagename=' . $pagename . '&pinsection=' . PIN_USER_SLUG . '&pinid=$matches[1]&pinsubsection=$matches[2]&paged=$matches[4]',
			'top');

		// Single category
		add_rewrite_rule(
			"^$pagename/([^/]*)/?",
			'index.php?pagename=' . $pagename . '&pinid=$matches[1]&pinsection=categoria',
			'top');
		
	
//		/flush_rewrite_rules();
		/*
		# Categorías
		#RewriteRule ^cosas-bellas/categorias/$ index.php?page-name=cosas-bellas&pinsection=categorias [L]
		# Paginación
		RewriteRule ^cosas-bellas(/page/([0-9]+)?)?/?$ index.php?pagename=cosas-bellas&&paged=$2 [L]
		# Categoría
		RewriteRule ^cosas-bellas/([^/]*)(/page/([0-9]+)?)?/?$ index.php?pagename=cosas-bellas&pinsection=categoria&pinid=$1&paged=$2 [L]
		RewriteRule ^cosas-bellas/([^/]*)(/page/([0-9]+)?)?/?$ index.php?pagename=cosas-bellas&pinsection=categoria&pinid=$1&paged=$2 [L]
		# Archive paginado
		RewriteRule ^cosas-bellas/([^/]*)/([^/]*)(/page/([0-9]+)?)?/?$ index.php?pagename=cosas-bellas&pinsection=$1&pinid=$2&paged=$4 [L]
		# Categoría
		*/
	}
	
	function add_query_vars($vars) {
		$vars[] = "pinsubsection";
	    $vars[] = "pinsection";
	    $vars[] = "pinid";
	    $vars[] = "edit";
	    return $vars;
	
	}
	
	/**
	 * Returns pins array depending on the parameters (user, category and number of pins)
	 *
	 * @param string $category
	 * @param int $user
	 * @param int $limit
	 */
	function get_pins ( $args = array() , $limit = PIN_PINS_PER_PAGE, $page = 1, $more_where = "") {
	
		global $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name;
		$table_boards_name = $table_name ."_boards";
		
		$where = "";
		
		if ( ! empty( $args )) {
			foreach ( $args as $key=>$value) {
				if ( empty($value))
					continue;
				if ( is_array( $value ))
					$where[] = "$key IN (" . implode(',', $value) . ")";
				else 
					$where[] = "$key = '$value'";
			}
		}
		if (! empty($where)) {
			$where = 'WHERE ' . implode(' AND ', $where);
		} else {
			$where = 'WHERE 1=1';
		}
		if (! empty( $more_where)) {
			$where = $where . ' ' . $more_where;
		}

		$page = ($page - 1) * $limit;

		$sql = "SELECT DISTINCT $table_name.*, $table_boards_name.`name` as board_name, $table_boards_name.slug as board_slug
		FROM $table_name  
		LEFT JOIN $table_boards_name ON $table_boards_name.id = $table_name.board
		$where 
		order by time DESC LIMIT $page, $limit";
		
//		dump($sql);
		return $wpdb->get_results ( $sql );
		
	}
	/**
	 * Returns single pin
	 *
	 * @param int $id
	 * @return object
	 */
	function get_pin ( $id ) {
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		$result = $wpdb->get_row ( "SELECT * FROM $table_name WHERE id = $id");
		
		if ( $result ) {
			
			$result->comments = $wpdb->get_results ( "SELECT * FROM {$table_name}_comments WHERE pin_id = $id ORDER BY time ASC");
		}
		
		return $result;
	}
	
	function add_pin ( $args ) {
	
		global $wpdb, $user_ID;
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		if ( ! is_user_logged_in())
			return false;
		
		global $current_user;
      	get_currentuserinfo();
		
		$args['time'] = date('Y-m-d H:i:s');
		$args['user_id'] = $current_user->ID;
		$args['user_name'] = $current_user->user_login;
		
		
		$wpdb->insert( $table_name , $args );
		
		// Actualizamos el contador de categorías
		//pin_update_cat_pins( $args['category'] );
		pin_update_board_pins( $_POST['board'] );
		
		$this->update_count_pins( $args['user_id'] );
		
		// Es un repin?
		if ( ! empty($args['parent'])) {
			// Actualizamos el contador de REPINES
			$count_repims = $wpdb->get_var ("SELECT count(*) FROM $table_name WHERE parent = '{$args['parent']}'");
			$wpdb->update( $table_name, array('repins' => $count_repims), array('id' => $args['parent']));
		}

		
		return $wpdb->insert_id;
	}
	
	function template_notices () {
		
		foreach ( $this->template_notices as $pin_notice ) {
			echo '<div class="alert alert-' . $pin_notice['type'] . '"><p>' . $pin_notice['message'] . '</p></div>';
		}
	
	}
	
	function add_template_notice ( $message, $type = 'alert') {
	
		array_push( $this->template_notices, array('message' => $message, 'type' => $type));
	}
	
	// BOARDS
	
	function add_board ( $name, $category ="", $description = "", $user_id = null, $board_id = null) {
		
		global $user_ID, $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name . '_boards';
		
		if ( ! isset($user_id))	
			$user_id = $user_ID;
			
		//Make sure the name is unique: if we've already got a user with this name, append a number to it.
	    $counter = 1;
	    $preslug = sanitize_title( $name );
	    if ( $this->board_exists( $preslug, $user_id, $board_id) ){
	        do{
	            $slug = $preslug;
	            $counter++;
	            $slug = $slug . '-' . $counter;
	        } while ( $this->board_exists( $slug, $user_id, $board_id) );
	    }else{
	    	$slug = $preslug;
	    }
	    
	    if ( $board_id ) {
	    	$wpdb->update ( $table_name, array('name' => $name, 'slug' => $slug, 'category' => $category, 'description' => $description, 'user_id' => $user_id), array('id' => $board_id));
	    } else {
	    	$wpdb->insert($table_name, array('name' => $name, 'slug' => $slug, 'category' => $category, 'description' => $description, 'user_id' => $user_id));
	    	$board_id = $wpdb->insert_id;
	    }
	    
	    return array( 'slug' => $slug, 'id' => $board_id);
	    
	}
	
	function add_header_script () {
		global $user_ID;
	?>
    <script type="text/javascript"> 
    	//var ajaxurl = '<?php echo site_url('wp-load.php'); ?>';
    	var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
     	var admin_ajax = '<?php echo admin_url('admin-ajax.php') ?>';
		var pinajaxurl = '<?php echo PIN_PLUGIN_AJAX ?>';
		var user_id = '<?php echo $user_ID?>';
		var pin_plugin_url = "<?php if ( is_multisite() ) echo network_site_url(); else site_url();?>wp-content/plugins/pin";
		var site_url = "<?php if ( is_multisite() ) echo network_site_url(); else site_url();?>";
    </script>
    <?
	}
	
	function board_exists( $slug, $user_id, $board_id = 0) {
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name . '_boards';
		
		return $wpdb->query("SELECT * FROM $table_name WHERE slug = '{$slug}' AND user_id = {$user_id} AND board_id != $board_id");
	}
	
	function delete_board ( $board_id ) {
	
	}

	function get_boards ( $user_id, $pins = 0) {
	
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name;
		$boards_table_name = $table_name . '_boards';
		
		$user_boards = $wpdb->get_results ("SELECT * FROM $boards_table_name WHERE user_id = $user_id ORDER BY pin_count DESC");
		
		if ( $pins > 0) {
			if ( count( $user_boards ) > 0 ){
				for ($i=0; $i < count($user_boards); $i++) {
					$user_boards[$i]->pins = $wpdb->get_col( "SELECT url FROM $table_name where board = {$user_boards[$i]->id} order by time DESC LIMIT 5");
				}
			}
		}
	
		return $user_boards;
	}
	
	function get_board ( $user_id, $slug, $id = null) {
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name . '_boards';
		
		if ( ! empty( $id )) {
			return $wpdb->get_row ("SELECT * FROM $table_name WHERE id = $id");
		} else {
			return $wpdb->get_row ("SELECT * FROM $table_name WHERE user_id = $user_id AND slug = '{$slug}'");
		}
		
		
	}
	
	function board_selector () {
		global $user_ID;
		$boards = $this->get_boards ( $user_ID, 0);
	?>
	
		<div class="control-group">
			<label class="control-label" for="board"><?php echo __('Selecciona un tablero', true); ?></label>
			<div class="BoardSelector BoardPicker controls">
			    <div class="current">
			        <span class="CurrentBoard"><?php if ( ! empty($boards) ) echo $boards[0]->name?></span>
			        <i class="icon-chevron-down DownArrow"></i>
			    </div>
			    <div class="BoardList" id="my_boards" style="display: none;">
			        <div class="wrapper">
			           <ul>
			            	<?php 

			            	
			            	if ( ! empty( $boards )) {
			            		foreach ( $boards as $board ) {
		            				echo '<li data="' . $board->id . '"><span>' . $board->name . '</span></li>';
			            		}
			            	}
			            	
			            	?>
			            </ul>
			            
			            <div class="CreateBoard">
			                <input type="text" value="Crear nuevo tablero" id="new-board-name" onfocus="if (this.value == 'Crear nuevo tablero') this.value = ''; " onblur="if (this.value == '') this.value = 'Crear nuevo tablero'; ">
			                <button type="button" class="btn">Crear</button>
			                <div class="CreateBoardStatus" style="display: none;"></div> 
			            </div>
			        </div>
			    </div>
			</div>
			<p class="hide">Por favor, indica el tablero</p>
		<input name="board" id="board" type="hidden" value="<?php if ( ! empty($boards) ) echo $boards[0]->id?>" />						
		</div>
		
		
		
	<?php
	

	}
	/**
	 * Seguir un tablero
	 *
	 * @param int $follower id del usuario
	 * @param int $followed id del usuario que se va a segui
	 * @param int $board id del tablero que va a seguir el $follower
	 */
	function follow_board ( $follower, $followed, $board ) {
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name . '_follow';
		
		$return = $wpdb->insert( $table_name, array( 'follower' => $follower, 'board' => $board, 'followed' => $followed));
		$this->update_board_followers($board);
		
		return $return;
	}
	
	/**
	 * Dejar de seguir un tablero
	 *
	 * @param int $follower id del usuario
	 * @param int $followed id del usuario que se va a segui
	 * @param int $board id del tablero que va a seguir el $follower
	 */
	function unfollow_board ( $follower, $followed, $board ) {
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name . '_follow';
		
		$return = $wpdb->query("DELETE FROM $table_name WHERE follower = $follower AND board = $board");
		$this->update_board_followers($board);
		
		return $return;
	}
	/**
	 * $follower deja de seguir todos los tableros de $followed
	 *
	 * @param int $follower
	 * @param int $followed
	 * @return unknown
	 */
	function unfollow_user ( $follower, $followed ) {
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->table_name . '_follow';
		
		return $wpdb->query("DELETE FROM $table_name WHERE follower = $follower AND followed = $followed");
	}
	
	/**
	 * $follower empieza a seguir todos los tableros de $followed
	 *
	 * @param int $follower
	 * @param int $followed
	 * @return unknown
	 */
	function follow_user ( $follower, $followed ) {
		
		$followed_boards = $this->get_boards( $followed, 0);
		
		foreach ( $followed_boards as $board) {
			$this->follow_board( $follower, $followed, $board->id );
		}
		
		return true;
		
		
	}
	
	function get_followers ( $user_id ) {
		global $wpdb;
		
		$pins_table = $wpdb->base_prefix . $this->table_name;
		$follow_table = $wpdb->base_prefix . $this->table_name . '_follow';
		
		$query = "SELECT DISTINCT follower as id, wp_users.display_name, wp_users.user_login,wp_users.ID
				 FROM wp_pin_follow
				 LEFT JOIN wp_users ON wp_pin_follow.follower = wp_users.ID
                 WHERE followed = $user_id AND follower <> $user_id";
	
		$users = $wpdb->get_results($query);
		
		for ( $i=0; $i<count($users); $i++) {
			$users[$i]->pins = $wpdb->get_col( "SELECT DISTINCT url FROM $pins_table where user_id = {$user_id} order by time DESC LIMIT 4");
		}
		//dump($users);
		return $users;
		
		
	}
	function get_following ( $user_id ) {
		global $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name;
		$follow_table = $table_name . '_follow';
		$boards_table = $table_name . '_boards';
		
		//return $wpdb->get_col("select distinct followed  from wp_pin_follow where follower = $user_id AND followed <> $user_id");
		$include = $wpdb->get_col("SELECT board FROM $follow_table WHERE follower = $user_id");
		$include_string = implode(',', $include);
		
		$boards = $wpdb->get_results("SELECT * FROM $boards_table WHERE id IN ( $include_string )");
		
		for ($i=0; $i < count($boards); $i++) {
			$boards[$i]->pins = $wpdb->get_col( "SELECT url FROM $table_name where board = {$boards[$i]->id} order by time DESC LIMIT 5");
		}
		return $boards;
		
	}
	
	
	function update_board_followers ( $board ) {
	
		global $wpdb, $wp_pin;
		
		$boards_table = $wpdb->base_prefix . $wp_pin->table_name . "_boards";
		$follow_table = $wpdb->base_prefix . $wp_pin->table_name . "_follow";
		
		// Actualizamos el contador de pins de un tablero
		$followers = $wpdb->get_var ("SELECT count(*) FROM $follow_table WHERE board = '{$board}'");
		$wpdb->update( $boards_table, array('followers' => $followers), array('id' => $board));
		
		return $followers;
	}
	function update_count_followers ( $user_id ) {
		global $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name;
		$follow_table = $table_name . '_follow';
		$boards_table = $table_name . '_boards';
		
		$count = $wpdb->get_var("SELECT COUNT(DISTINCT follower) as total FROM $follow_table WHERE followed = $user_id AND follower <> $user_id");
		add_user_meta( $user_id, 'pin_count_followers', $count, true);
		
		return $count;
	}
	function update_count_following ( $user_id ) {
		global $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name;
		$follow_table = $table_name . '_follow';
		$boards_table = $table_name . '_boards';
		
		$count = $wpdb->get_var("SELECT COUNT(DISTINCT id) as total FROM $follow_table WHERE follower = $user_id");
		add_user_meta( $user_id, 'pin_count_following', $count, true);
		
		return $count;
	}
	function update_count_pins ( $user_id ) {
		global $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name;
		
		$count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id");
		update_user_meta( $user_id, 'pin_count_pins', $count );
		
		return $count;
	}
	function update_count_boards ( $user_id ) {
		global $wpdb;
		
		$table_name = $wpdb->base_prefix . $this->table_name . '_boards';
		
		$count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id");
		add_user_meta( $user_id, 'pin_count_boards', $count, true);
		
		return $count;
	}
	
}

global $wp_pin;
$wp_pin = new Pin();