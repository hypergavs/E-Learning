<?php
function importStyle(){
	wp_enqueue_script('jquery-script', get_template_directory_uri().'/js/jquery-3.2.1.js');
	
	wp_enqueue_script('materialize-script',get_template_directory_uri().'/materialize/js/materialize.js');
	wp_enqueue_script('jqueryui-script', get_template_directory_uri().'/jquery-ui/jquery-ui.min.js');
	wp_enqueue_style('jqueryui-style',get_template_directory_uri().'/jquery-ui/jquery-ui.min.css');
	wp_enqueue_style('google-font','https://fonts.googleapis.com/css?family=Open+Sans');
	wp_enqueue_style('google-font-2','https://fonts.googleapis.com/css?family=Source+Sans+Pro');
	wp_enqueue_style('jqueryui-theme',get_template_directory_uri().'/jquery-ui/jquery-ui.theme.css');
	//---------
	wp_enqueue_style('bootstrap-style',get_template_directory_uri().'/mdb-new/css/bootstrap.css');
	wp_enqueue_style('bootstrap-fontawesome',get_template_directory_uri().'/font-awesome-4.7.0/css/font-awesome.css');
	wp_enqueue_script('bootstrap-popper',get_template_directory_uri().'/js/popper.min.js');
	wp_enqueue_script('bootstrap-script',get_template_directory_uri().'/mdb-new/js/bootstrap.min.js');
	
	wp_enqueue_style('jquery-datatable-theme', get_template_directory_uri().'/css/jquery.dataTables.min.css');
	wp_enqueue_style('buttons-datatable-theme', get_template_directory_uri().'/css/buttons.dataTables.min.css');
	wp_enqueue_style('bootstrap-datatable-theme', get_template_directory_uri().'/css/dataTables.bootstrap4.min.css');
	
	wp_enqueue_script('jquery-datatable',get_template_directory_uri().'/js/jquery.dataTables.min.js');
	wp_enqueue_script('bootstrap-datatable',get_template_directory_uri().'/js/dataTables.bootstrap4.min.js');
	wp_enqueue_script('buttons-datatable',get_template_directory_uri().'/js/dataTables.buttons.min.js');
	wp_enqueue_script('buttons-print-datatable',get_template_directory_uri().'/js/buttons.print.min.js');
	
	
	//text editor
	wp_enqueue_style('text-editor-css', get_template_directory_uri().'/plugins/wysiwyg/editor.css');
	wp_enqueue_script('text-editor-js',get_template_directory_uri().'/plugins/wysiwyg/editor.js');
	
	wp_enqueue_style('mdb-theme', get_template_directory_uri().'/mdb-new/css/mdb.css');
	
	wp_enqueue_script('io-script', get_template_directory_uri().'/js/socket.io.js');
	wp_enqueue_script('custom-script', get_template_directory_uri().'/js/custom-script.js');
	wp_enqueue_style('style', get_stylesheet_uri());;
}



add_action('wp_enqueue_scripts', 'importStyle');

register_nav_menus(array(
	'login'=>__('Login'),
	'side_nav'=>__('Side Navigation'),
	'admin_nav'=>__('Admin Navigation'),
	'primary'=>__('Primary'),
	'footer'=>__('Footer'),
));
?>