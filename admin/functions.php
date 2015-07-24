<?php
// Register Option Groups
add_action( 'admin_init', 'jte_option_groups' );
function jte_option_groups() {
	register_setting( 'jte_settings_group', 'jte_settings', 'jte_settings_valid' );
	register_setting( 'jte_bulk_mail_group', 'jte_bulk_mail', 'jte_bulk_mail_valid' );
	
	register_setting( 'general', 'jte_wp_mail_name', 'esc_attr' );
	add_settings_field( 'jte_wp_mail_name', '<label for="extra_blog_desc_id">' . __('E-Mail Sender', 'jte') . '</label>', 'jte_wp_mail_name', 'general');
	register_setting( 'general', 'jte_wp_mail_address', 'esc_attr' );
	add_settings_field( 'jte_wp_mail_address', '<label for="extra_blog_desc_id">' . __('E-Mail Sender Address', 'jte') . '</label>', 'jte_wp_mail_address', 'general');
}

// Add Option Pages in Admin Menu
add_action( 'admin_menu', 'jte_add_admin_menu' );
function jte_add_admin_menu() { 
	add_menu_page( __('Overview', 'jte'), __('Guestlist', 'jte'), 'manage_options', 'guestlist', 'jte_overview_page', 'dashicons-clipboard', 25 );
	add_submenu_page( 'guestlist', __('Overview', 'jte'), __('Overview', 'jte'), 'manage_options', 'guestlist', 'jte_overview_page' );
	add_menu_page( __('Join the Event (Settings)', 'jte'), __('Join the Event', 'jte'), 'manage_options', 'join_the_event', 'jte_settings_page', plugin_url() . 'icon.png', 81 );
	add_submenu_page( 'join_the_event', __('Settings', 'jte'), __('Settings', 'jte'), 'manage_options', 'join_the_event', 'jte_settings_page' );
	add_submenu_page( 'join_the_event', __('Bulk Mail', 'jte'), __('Bulk Mail', 'jte'), 'manage_options', 'jte_bulk_mail', 'jte_bulk_mail_page' );
}

// This will show Wordpress Mail Settings on the general options page
function jte_wp_mail_name() { ?>
	<input id="jte_wp_mail_name" class="regular-text" type="text" value="<?php echo get_option('jte_wp_mail_name');?>" name="jte_wp_mail_name"/>
    <p class="description"><?php _e('Changes the sender name of mails sent by Wordpress as long as the Plugin &bdquo;Join the Event&rdquo; is active.', 'jte');?></p>
<?php }
function jte_wp_mail_address() { ?>
	<input id="jte_wp_mail_address" class="regular-text" type="email" value="<?php echo get_option('jte_wp_mail_address');?>" name="jte_wp_mail_address"/>
    <p class="description"><?php _e('Changes the sender mail address of mails sent by Wordpress as long as the Plugin &bdquo;Join the Event&rdquo; is active.', 'jte');?></p>
<?php }

// This will show below the color scheme and above username field
add_action( 'show_user_profile', 'jte_profile_setting' );
function jte_profile_setting( $user ) {
    $meta_value = get_user_meta( $user->ID, 'jte_receivemail', true);
    ?>
    <h3>Join the Event</h3>
    <table class="form-table">
    	<tr><th>
        	<?php fa_circle('fa-envelope-o', true);?> <?php _e('New Guest', 'jte');?>
        </th><td>
        	<input id="jte_receivemail" type="checkbox" value="1" name="jte_receivemail" <?php if ($meta_value == 1) echo 'checked="checked"';?>/><label for="jte_receivemail"><?php _e('Receive Mail when a new guest has registered', 'jte');?></label>
			<?php $options = get_option('jte_settings');
			if ($options['email']['confirmation'] !== "1") { ?>
            	<p class="description"><?php _e('<b>Currently no user will receive a confirmation mail.</b> First change this on the Setting Page of the Plugin in order to configure your personal setting:', 'jte');?> <a href="<?php echo admin_url('admin.php?page=join_the_event#confirmation_mail');?>">Join the Event – <?php _e('Settings', 'jte');?></a></p>
			<?php } ;?>        
        </td></tr>
    </table>
    <?php
}

add_action( 'personal_options_update', 'jte_update_user_setting');
function jte_update_user_setting() {
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'jte_receivemail', $_POST['jte_receivemail']);
}

/**
 *	Styles and Scripts
 */
 
 	// Register Styles and Scripts
	add_action( 'init', 'jte_register' );
	function jte_register() {
		wp_register_style( 'jte_admin_css', plugins_url('join-the-event') . '/css/jte-admin.css', NULL, NULL, 'screen' );
		wp_register_style( 'font-awesome', plugins_url('join-the-event') . '/css/font-awesome.min.css', NULL, NULL, 'all' );
		wp_register_style( 'jquery-ui-datepicker', plugins_url('join-the-event') . '/css/jquery-ui.css', NULL, NULL, 'screen');
		wp_register_script('settings-script', plugins_url('join-the-event') . '/js/settings.js', array('jquery'), NULL, true);
	}
	
	// Enqueue Styles and Scripts
	add_action( 'admin_enqueue_scripts', 'jte_enqueue_admin_scripts' );
	function jte_enqueue_admin_scripts(){
		wp_enqueue_style('jte_admin_css');
		wp_enqueue_style('font-awesome');
		wp_enqueue_style('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('settings-script', false, false, NULL, true);
	}

	add_action( 'wp_enqueue_scripts', 'jte_enqueue_scripts' );
	function jte_enqueue_scripts(){
		wp_enqueue_style('font-awesome');
	}

/**
 *	Register Post Types
 */
	
	// Add guest
	add_action( 'init', 'jte_post_types' );
	function jte_post_types() {
		register_post_type( 'guest',
			array(
				'labels' => array(
					'name' => __('Guests', 'jte'),
					'singular_name' => __('Guest', 'jte'),
					'view_item' => ''
				),
				'public' => false,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'has_archive' => false,
				'publicly_queryable'  => false,
				'query_var' => false,
				'hierarchical' => false,
				'supports' => array('title'),
				'menu_icon' => 'dashicons-clipboard',
				'show_in_menu' => 'guestlist'
			)
		);
		remove_post_type_support( 'guest', 'thumbnail' );
		remove_post_type_support( 'guest', 'page-attributes' );
		remove_post_type_support( 'guest', 'revisions' );
		
		// Add guestlist
		register_post_type( 'guestlist',
			array(
				'labels' => array(
					'name' => __('Guestlist', 'jte'),
					'singular_name' => __('Guestlist', 'jte')
				),
				'public' => true,
				'show_in_menu' => false,
				'supports' => array('title'),
				'capabilities' => array(
					'publish_posts' => 'publish_guestlist',
					'edit_posts' => 'edit_guestlist',
					'edit_others_posts' => 'edit_others_guestlist',
					'delete_posts' => 'delete_guestlist',
					'delete_others_posts' => 'delete_others_guestlist',
					'read_private_posts' => 'read_private_guestlist',
					'edit_post' => 'edit_guestlist',
					'delete_post' => 'delete_guestlist',
					'read_post' => 'read_guestlist',
				)
			)
		);
		flush_rewrite_rules();
	}
	
	// Remove guest update messages
	add_filter( 'post_updated_messages', 'guest_update_messages' );
	function guest_update_messages($messages) {
			$messages[$post_type] = array();
			return $messages;
	}

/**	
 *	FONTAWESOME FUNCTIONS
 *	Find more information about FontAwesome here: http://fortawesome.github.io/Font-Awesome/
 *
 */

	// Output Basic FontAwesome Icons
	function fa($input, $echo) {
		$output = '<i class="fa ' . $input . ' fa-fw"></i>';
		if ($echo !== false) {
			echo $output;
		} else {
			return $output;
		}
	}
	
	// Output Circled FontAwesome Icons
	function fa_circle($input, $echo) {
		$output = '<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x fa-fw"></i><i class="fa ' . $input . ' fa-stack-1x fa-inverse"></i></span>';
		if ($echo !== false) {
			echo $output;
		} else {
			return $output;
		}
	}

/**
 *	General WP-Modifications
 */

	// Change default Wordpress Mail Configuration
	add_filter('wp_mail_from', 'new_mail_from');
	add_filter('wp_mail_from_name', 'new_mail_from_name');
	function new_mail_from($old) {
		$new = get_option('jte_wp_mail_address');
		if ($new && $new !== "") {
			return $new;
		} else {
			return $old;
		}
	}
	function new_mail_from_name($old) {
		$new = get_option('jte_wp_mail_name');
		if ($new && $new !== "") {
			return $new;
		} else {
			return $old;
		}
	}
	
	// Stop Linebreak-Removing in WYSIWYG-Editor
	add_filter('tiny_mce_before_init', 'jte_editor');
	function jte_editor($settings) {
	  $settings['remove_linebreaks'] = false;
	  $settings['remove_redundant_brs'] = false;
	  $settings['wpautop'] = false;
	  return $settings;
	}
?>