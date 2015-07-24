<?php
/*
Plugin Name: Join the Event
Plugin URI: http://adriankinas.de/join-the-event
Description: Make use of a solid and Ajax supporting RSVP Plugin that manages a guestlist for your upcoming event.
Version: 1.0.1
Author: Adrian Kinas
Author Email: contact@adriankinas.de
License: GPLv2
*/

// Save Plugin-Path in global Function
function plugin_directory() {
	return plugin_dir_path( __FILE__ );
}

function plugin_url() {
	return plugin_dir_url( __FILE__ );
}

// Initialisation Action
register_activation_hook(__FILE__,'init_jte');
function init_jte() {
	$args = array('orderby' => 'display_name');
	$wp_user_query = new WP_User_Query($args);
	$users = $wp_user_query->get_results();
	foreach ($users as $user) {
		update_user_meta($user->ID, 'jte_receivemail', 1);
	}
}

// Shortcode-Notice after Plugin Activation
add_action( 'admin_notices', 'jte_notice' );
function jte_notice() {
	if(!get_option('jte_notice')) {
		?>
		<div class="updated notice is-dismissible">
			<p><?php _e( 'Thank you for using &bdquo;Join the Event&rdquo;! You can now use the RSVP-Form by using the shortcode <code>[join-the-event]</code>.', 'jte' ); ?></p>
		</div>
    <?php add_option('jte_notice', 'false');
	}
}

// Redirect after Plugin Activation
add_action( 'activated_plugin', 'activated_jte' );
function activated_jte( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
		$post_args = array(
			'post_title' => __('Guestlist', 'jte'),
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'guestlist'
		);
		$post_id = wp_insert_post($post_args);
		update_option('jte_guestlist_id', $post_id);
        exit(wp_redirect(admin_url('admin.php?page=join_the_event')));
    }
}

// Deactivation Action
register_deactivation_hook(__FILE__,'remove_jte');
function remove_jte() {
	delete_option('jte_notice');
	wp_delete_post(get_option('jte_guestlist_is'), true);
}

// Load Language File
load_textdomain('jte', plugin_directory() . '/lang/join-the-event-' . get_locale() . '.mo');

// Load Plugin Elements
require_once plugin_directory() . 'admin/functions.php';
if (is_admin()) {
	require_once plugin_directory() . 'admin/menu-pages/settings.php';
	require_once plugin_directory() . 'admin/menu-pages/bulk-mail.php';
	require_once plugin_directory() . 'admin/menu-pages/overview.php';
	require_once plugin_directory() . 'admin/post-types/guest.php';
	require_once plugin_directory() . 'mail/functions.php';
} else {
	require_once plugin_directory() . 'form/functions.php';
}

// Register the Widget
class jteWidget extends WP_Widget {
	function __construct() {
		parent::__construct( 
			false,
			'Join the Event Form',
			array(
				'description' => __('Add the eventform to your sidebar.', 'jte')
			)
		);
	}
	function widget() {
		echo do_shortcode('[join-the-event]');
	}
}

add_action( 'widgets_init', 'register_jteWidget' );
function register_jteWidget() {
	register_widget( 'jteWidget' );
}

/**
 *	ADD AJAX FORM ACTION
 *	See form/functions.php
 */
add_action('wp_ajax_jte_post_guest', 'jte_post_guest');
add_action('wp_ajax_nopriv_jte_post_guest', 'jte_post_guest');
function jte_post_guest() {
		if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'jte_nonce')) {
			$options = get_option('jte_settings');
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'guest',
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => '_jte_guest_mail',
						'value' => wp_kses($_POST['mail']),
						'compare' => '='
					),
					array(
						'relation' => 'AND',
						array(
							'key' => '_jte_guest_firstname',
							'value' => wp_kses($_POST['firstname']),
							'compare' => '='
						),
						array(
							'key' => '_jte_guest_surname',
							'value' => wp_kses($_POST['surname']),
							'compare' => '='
						),
					)
				)
			);
			$posts = get_posts($args);
			if (count($posts) !== 0) { ?>
				<div id="jte_wrapper">
					<?php fa_circle('fa-times', true); ?>
					<p><?php echo $options['form-texts']['error'];?></p>
					<div id="social_box">
						<?php if ($ics = $options['event']['ics-url']) :?>
						<a href="<?php echo $ics;?>" download="">
							<?php fa('fa-calendar-o fa-fw', true);?>
							Add to your Calendar
						</a>
						<?php endif; if ($fb = $options['event']['facebook']) :?>
						<a href="<?php echo $fb;?>" target="_blank">
							<?php fa('fa-facebook fa-fw', true);?>
							Follow us on Facebook
						</a>
						<?php endif;?>
					</div>
				</div>
			<?php } else {
				$post_args = array(
					'post_title' => wp_kses($_POST['firstname'] . ' ' . $_POST['surname']),
					'post_content' => '',
					'post_status' => 'publish',
					'post_type' => 'guest'
				);
				$guest_id = wp_insert_post($post_args);
				
				add_post_meta($guest_id, '_jte_guest_firstname', wp_kses($_POST['firstname']));
				add_post_meta($guest_id, '_jte_guest_surname', wp_kses($_POST['surname']));
				add_post_meta($guest_id, '_jte_guest_mail', wp_kses($_POST['mail']));
				add_post_meta($guest_id, '_jte_guest_escort', $plus = (wp_kses($_POST['plus']) > 0) ? wp_kses($_POST['plus']) : 0);	
				jte_new_guest_mail();
				?>
				<div id="jte_wrapper">
					<?php fa_circle('fa-check', true); ?>
					<p><?php echo $options['form-texts']['response'];?></p>
					<div id="social_box">
						<?php if ($ics = $options['event']['ics-url']) :?>
						<a href="<?php echo $ics;?>" download="">
							<?php fa('fa-calendar-o fa-fw', true);?>
							Add to your Calendar
						</a>
						<?php endif; if ($fb = $options['event']['facebook']) :?>
						<a href="<?php echo $fb;?>" target="_blank">
							<?php fa('fa-facebook fa-fw', true);?>
							Follow us on Facebook
						</a>
						<?php endif;?>
					</div>
				</div>
			<?php } 
		}
	die();
}

/**
 *	ADD TEMPLATE FOR PRINTABLE GUESTLIST
 *	See templates/guestlist.php
 */ 
add_filter( 'template_include', 'jte_guestlist_template', 99 );
function jte_guestlist_template( $page_template )
{
    if ( is_singular( 'guestlist' ) ) {
        $page_template = plugin_directory() . '/templates/guestlist.php';
    }
    return $page_template;
}

?>