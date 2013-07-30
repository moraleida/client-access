<?php
/**
 * @package client-access
 * @version 0.1
 */
/*
Plugin Name: Client Access
Plugin URI: http://wordpress.org/extend/plugins/client-access/
Description: A concise plugin adding simple client access and relationship for Freelance WordPress Developers
Author: Ricardo Moraleida
Version: 0.1
Author URI: http://moraleida.me/
*/

define('CLIENTACCESS_VERSION', '0.1');
define('CLIENTACCESS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('CLIENTACCESS_PATH', dirname( __FILE__ ));

add_action('init', 'client_access_post_types');
add_action('admin_menu', 'client_access_menu_pages');
add_action('save_post', 'client_save_postdata');
add_action('wp_enqueue_scripts','client_access_scripts_styles');
// add_action('admin_print_scripts','client_access_scripts_styles');
add_action('wp_print_styles','client_access_remove_styles');
add_action( 'wp_ajax_client_access_ajax_make_pdf', 'client_access_ajax_make_pdf' );
add_action( 'wp_ajax_nopriv_client_access_ajax_make_pdf', 'client_access_ajax_make_pdf' );
add_action( 'wp_ajax_client_access_ajax_delete_pdf', 'client_access_ajax_delete_pdf' );
add_action( 'wp_ajax_nopriv_client_access_ajax_delete_pdf', 'client_access_ajax_delete_pdf' );

add_filter('single_template', 'client_access_templates');

/**
 * client_access_post_types()
 * 
 * Creates the initial post types and calls their meta box creation functions
 * 
 * @return type
 */
function client_access_post_types() {

	$clients 	 = array( 'register_meta_box_cb' => 'clients_meta_box', 'public' => true, 'label' => 'Clients', 'hierarchical' => true );
	$projects 	 = array( 'register_meta_box_cb' => 'projects_meta_box', 'public' => true, 'label' => 'Projects' );
	$timeentries = array( 'register_meta_box_cb' => 'timeentries_meta_box', 'public' => true, 'label' => 'Time Entries' );
	$bids 		 = array( 'register_meta_box_cb' => 'bids_meta_box', 'public' => true, 'label' => 'Bids' );
	$contracts 	 = array( 'register_meta_box_cb' => 'contracts_meta_box', 'public' => true, 'label' => 'Contracts' );

    register_post_type( 'client', $clients );
    register_post_type( 'project', $projects );
    register_post_type( 'timeentry', $timeentries );
    register_post_type( 'bid', $bids );
    register_post_type( 'contract', $contracts );
}

function client_access_menu_pages() {

	add_menu_page('Client Access', 'Client Access', 'publish_posts', 'client-access/inc/client_defaults.php');

}

function clients_meta_box() {
	include_once(CLIENTACCESS_PATH.'/inc/clients_meta.php');
	add_meta_box('clients', 'Details', 'clients_meta_html');
}

function projects_meta_box() {
	include_once(CLIENTACCESS_PATH.'/inc/projects_meta.php');
	add_meta_box('projects', 'Details', 'projects_meta_html');
}

function timeentries_meta_box() {
	include_once(CLIENTACCESS_PATH.'/inc/timeentries_meta.php');
	add_meta_box('timeentries', 'Details', 'timeentries_meta_html');
}

function bids_meta_box() {
	include_once(CLIENTACCESS_PATH.'/inc/bids_meta.php');
	add_meta_box('values', 'Values', 'bids_meta_values_html');
	add_meta_box('bids', 'Details', 'bids_meta_html');
}

function contracts_meta_box() {
	include_once(CLIENTACCESS_PATH.'/inc/contracts_meta.php');
	add_meta_box('contracts', 'Details', 'contracts_meta_html');
}


/**
 * client_save_postdata($postid)
 * 
 * Does initial validation and permissions checkings, then routes save requests 
 * from the plugins post types to their appropriate functions
 *  
 * @param int $postid 
 * @return bool
 */
function client_save_postdata($postid) {

	if($_POST['post_type']) :

		$posttype = $_POST['post_type'];

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	      return;

	    if ( !check_admin_referer( 'clientaccess_save_post', 'clientaccess_nonce' ) )
	      return;

	  	if( current_user_can('publish_posts', $postid)) {
	  		include_once(CLIENTACCESS_PATH.'/inc/save_post_meta.php');

		  	$save = new ClientAccess_SaveData();
		  	$save->save($posttype, $postid, $_POST);
  	}

  	endif;

}


/**
 * Routes access to the Custom Post Types to their default template counterparts
 * @param type $single 
 * @return type
 */


function client_access_templates($single) {
    global $wp_query, $post;

	// Check if the user has created his own custom templates and, if so, use them
	if(file_exists(get_template_directory(). '/clientaccess/'.$post->post_type.'_template.php')) {
	
		return get_template_directory(). '/clientaccess/'.$post->post_type.'_template.php';
	
	} 
	// If not, then use our own bundled templates
	else if(file_exists(CLIENTACCESS_PATH. '/templates/'.$post->post_type.'_template.php')) {
	
		return CLIENTACCESS_PATH . '/templates/'.$post->post_type.'_template.php';
	}
	    
	
	return $single;
}

function client_access_scripts_styles() {

	wp_register_script('init', CLIENTACCESS_PLUGIN_URL.'/js/init.js', array('jquery'), true);
    wp_register_script('html-tables', CLIENTACCESS_PLUGIN_URL.'/js/html-tables.js', array('jquery'), true);

	if(is_admin()) {
		global $wp_styles;
    	//$wp_styles->queue = array();

    	
	    
	    wp_enqueue_script('html-tables');
	        
	    

	}
	

	wp_enqueue_script('jquery');   
    wp_enqueue_script('init');
    wp_localize_script( 'init', 'CaAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
   
    

}

function client_access_remove_styles() {

	global $wp_styles;
    $wp_styles->queue = array();

}


/**
 * Description
 * @return type
 */

function client_access_ajax_make_pdf() {

	include_once(CLIENTACCESS_PATH.'/lib/mpdf/mpdf.php');

	$html = $_POST['source'];

	$updir = wp_upload_dir();
	$finaldir = $updir['basedir'] . '/pdf/';
	$time = time();

	// $file = tempnam($finaldir, time());	
	$file = $finaldir . $time .'.pdf';
	$url = $updir['baseurl'] . '/pdf/' . $time . '.pdf';
	$stylesheet = file_get_contents($_POST['stylesheet']);

	$mpdf = new mPDF('utf-8','A4','','' , 20 , 20 , 20 , 20 , 20 , 20); 
	$mpdf->SetDisplayMode('fullpage');

	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
	$mpdf->WriteHTML($stylesheet, 1);
	$mpdf->WriteHTML($html, 0);
	$mpdf->Output($file, 'f');

	$response = array($url,$file);
 
 	header( "Content-Type: application/json" );
    echo json_encode($response);
 
    exit;

}

function client_access_ajax_delete_pdf() {

	$file = $_POST['target'];
	unlink($file);

	$response = 'ok';
 
 	header( "Content-Type: application/json" );
    echo json_encode($response);
 
    exit;

}
?>