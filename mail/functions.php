<?php 
/**
 *	SEND BULK MAILS AFTER REGISTRATION
 */
 
function jte_new_guest_mail() {
	$options = get_option('jte_settings');
	$firstname = $_POST['firstname'];
	$surname = wp_kses($_POST['surname']);
	$guest = wp_kses($firstname . ' ' . $surname);
	$plus = wp_kses($_POST['plus']);
	$link = get_bloginfo('url') . '/wp-admin/edit.php?post_type=guest';
	if ($options['email']['confirmation'] == 1) {
		if ($plus > 1) {
			$subject = sprintf(__('%s and %d more guests will take part on your event', 'jte'), $guest, $plus);
			$content = sprintf(__("<p>Hello together,</p><p>%s and %d more guests will take part on your event.</p><p>For more information please visit " . $link . "</p>", 'jte'), $guest, $plus);
		} elseif ($plus == 1) {
			$subject = sprintf(__('%s and %d more guest will take part on your event', 'jte'), $guest, $plus);
			$content = sprintf(__("<p>Hello together,</p><p>%s and %d more guest will take part on your event.</p><p>For more information please visit " . $link . "</p>", 'jte'), $guest, $plus);
		} else {
			$subject = sprintf(__('%s will take part on your event', 'jte'), $guest);
			$content = sprintf(__("<p>Hello together,</p><p>%s will take part on your event.</p><p>For more information please visit " . $link . "</p>", 'jte'), $guest, $plus);
		}
		$content = wp_kses_post($content);
		$emails = array();
		$args = array('orderby' => 'display_name');
		$wp_user_query = new WP_User_Query($args);
		$users = $wp_user_query->get_results();
		foreach ($users as $user) {
			if (get_user_meta($user->ID, 'jte_receivemail', true) == "1") {
				$user_info = get_userdata($user->ID);
				$emails[] = $user_info->user_email;
			}
		}
		// To each host
		foreach($emails as $email) {
			wp_mail( $email, $subject, $content, array('Content-Type: text/html; charset=UTF-8') );
		}
	}
	$subject = $options['email']['subject'];
	$text = $options['email']['text'];
	$text = str_replace('[firstname]', $firstname, $text);
	$text = str_replace('[surname]', $surname, $text);
	$text = str_replace('[plus]', $plus, $text);
	$text = wp_kses_post($text);
	// To registered guest
	wp_mail( $_POST['mail'], $subject, $text, array('Content-Type: text/html; charset=UTF-8') );
}

// Custom Bulk Mail
function jte_bulk_mail_valid($input) {
	$emails = array();
	$guests = $_POST['jte_bulk_mail']['receivers']['guests'];
	$hosts = $_POST['jte_bulk_mail']['receivers']['hosts'];
	$user = $_POST['jte_bulk_mail']['receivers']['user'];
	if (isset($guests)) {
		$args = array(
			'post_type' => 'guest',
			'post_status' => array('publish','pending'),	
			'posts_per_page' => -1,
		);
		$guestlist = get_posts($args);
		$i = 0;
		foreach ($guestlist as $post) {
			setup_postdata($guest);
			$emails[] = get_post_meta($post->ID, '_jte_guest_mail', true);
		}
		wp_reset_postdata();	
	}
	if (isset($hosts)) {
		$args = array('orderby' => 'display_name');
		$wp_user_query = new WP_User_Query($args);
		$authors = $wp_user_query->get_results();
		if (!empty($authors)) {
			foreach ($authors as $author) {
				$author_info = get_userdata($author->ID);
				$emails[] = $author_info->user_email;
			}
		}
	}
	if (isset($user) && !isset($hosts)) {
		$current_user = wp_get_current_user();
		$emails[] = $current_user->user_email;
	}
	$subject = wp_kses($_POST['jte_bulk_mail']['subject']);
	$text = wp_kses_post($_POST['jte_bulk_mail']['text']);
	foreach($emails as $email) {
		wp_mail( $email, $subject, $text, array('Content-Type: text/html; charset=UTF-8') );
	}
	$message = null;
    $type = null;
    if (!empty($emails)) {
		$type = 'updated';
		$message = __( 'Bulk Mail has been send successfully.', 'jte' );
    } else {
        $type = 'error';
        $message = __( 'Data can not be empty', 'jte' );
    }
    add_settings_error('bulk-mail-errors', 'settings_updated', $message, $type);
}
?>