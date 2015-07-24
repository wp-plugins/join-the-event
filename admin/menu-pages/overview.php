<?php function jte_overview_page() { 
	$args = array(
		'post_type' => 'guest',
  		'post_status' => array('publish','pending'),	
		'meta_key' => '_jte_guest_mail',
		'meta_value' => $mail,
		'posts_per_page' => -1,
	);
	$guestlist = get_posts($args);
	$i = 0;	$j = 0;
	$mails = array();
	foreach ($guestlist as $guest) {
		setup_postdata($guest);
		$mails[$i]['mail'] = get_post_meta($guest->ID, '_jte_guest_mail', true);
		$mails[$i]['name'] = get_post_meta($guest->ID, '_jte_guest_firstname', true) . ' ' . get_post_meta($guest->ID, '_jte_guest_surname', true);
		$i++;
		$j += get_post_meta($guest->ID, '_jte_guest_escort', true);
	}
	wp_reset_postdata();

?>
	<div class="metabox-holder has-right-sidebar" id="poststuff">
        <div id="post-body">
            <div id="post-body-content">
                <h1><?php _e('Overview', 'jte');?></h1>
                <div class="meta-box"><div class="acf_postbox postbox default">
                    <div class="inside">
                    <table><tr>
                    	<td style="padding: 0 15px 0 0"><?php fa_circle('fa-user');?> <?php _e('Number of Confirmations:', 'jte');?></td>
                        <td><b><?php echo $i;?></b></td>
                    </tr><tr>
                    	<td style="padding: 0 15px 0 0"><?php fa_circle('fa-users');?> <?php _e('Number of Confirmations incl. Escorts:', 'jte');?></td>
                        <td><b><?php echo ($i + $j);?></b></td>
                    </tr></table></p>
                    <textarea rows="5" id="mailverteiler" class="textarea" name="" readonly="readonly" style="width: 100%;" onclick="this.select()">
<?php if ($mails) {
	$mailto = '';
	foreach ($mails as $mail) {
		$textarea = $textarea . $mail['mail'] . ' (' . $mail['name'] .  '), ';
		$mailto = $mailto . $mail['name'] . ' <' . $mail['mail'] . '>,';
	}
	$textarea = substr($textarea, 0, -2);
	echo $textarea;
	$mailto = substr($mailto, 0, -1);
} else {
	_e('No mail addresses available', 'jte');
}?>
                    </textarea>
                    <?php if ($mails) :?><p><a href="/wp-admin/admin.php?page=jte_bulk_mail" class="acf-button button button-primary button-large"><?php fa('fa-users');?> <?php _e('Bulk Mail', 'jte');?></a><a href="mailto:<?php echo $mailto;?>?subject=<?php bloginfo('name');?>" class="acf-button button button-primary button-large" style="margin: 0 0 0 10px"><?php fa('fa-envelope-o');?> <?php _e('Generate Mail', 'jte');?></a><a href="<?php echo get_post_permalink(get_option('jte_guestlist_id'));?>" class="acf-button button button-primary button-large" style="margin: 0 0 0 10px" target="_blank"><?php fa('fa-print');?> <?php _e('Printable Guestlist', 'jte');?></a></p><?php endif;?>
                    </div>
                </div>
            </div>
        </div>
	</div>        
<?php } ?>