<?php 
$options = get_option('jte_settings');
add_shortcode('join-the-event', 'jte_shortcode');
function jte_shortcode() {
	require_once plugin_directory() . 'form/jte-form.php';
};

if($options['event']['status'] == 1) { 	
	add_action( 'wp_enqueue_scripts', 'jte_enqueue_form_script', 15 );
	function jte_enqueue_form_script(){
		if (is_singular('guestlist')) {
			wp_enqueue_style('jte-guestlist', plugins_url('join-the-event') . '/css/jte-guestlist.css', NULL, NULL, 'all');
			wp_enqueue_style( 'normalize', plugins_url('join-the-event') . '/css/normalize.min.css', NULL, false, 'all' );
		}
		wp_enqueue_style('jte-form', plugins_url('join-the-event') . '/css/jte-form.css', NULL, NULL, 'all');
		wp_enqueue_script('jte-form', plugins_url('join-the-event') . '/js/form.js', array('jquery'), NULL, true);
		wp_localize_script( 'jte-form', 'jteAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
	}
}
?>