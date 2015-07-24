<?php function jte_bulk_mail_page() {
	wp_enqueue_media();
	?>
	<div class="wrap">
		<h2><?php _e('Join the Event (Bulk Mail)', 'jte');?></h2>
        <?php settings_errors('bulk-mail-errors');?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=join_the_event" class="nav-tab"><?php fa('fa-cog');?> <?php _e('Settings', 'jte');?></a>
            <a href="#" class="nav-tab nav-tab-active"><?php fa('fa-envelope-o');?> <?php _e('Bulk Mail', 'jte');?></a>
        </h2>
		<form action="options.php" method="post" id="post">
        <?php settings_fields('jte_bulk_mail_group');
		$options = get_option('jte_bulk_mail');?>
		<div id="poststuff" class="metabox has-right-sidebar">   
            <div id="post-body">
                <div id="post-body-content">
                    <div id="normal-sortables" class="meta-box">
                        <div class="postbox">
                            <h3 class="hndle"><span><?php fa('fa-users');?> <?php _e('Receivers', 'jte');?></span></h3>
                            <div class="inside">
                            	<table class="jte-table"><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="receivers-guests"><?php fa_circle('fa-ticket');?> <?php _e('Guests', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><input type="checkbox" id="receivers-guests" name="jte_bulk_mail[receivers][guests]" value="1" <?php checked(1, $options['receivers']['guests']);?>/></td>
                                </tr><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="receivers-hosts"><?php fa_circle('fa-home ');?> <?php _e('All Hosts', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><input type="checkbox" id="receivers-hosts" name="jte_bulk_mail[receivers][hosts]" value="1" <?php checked(1, $options['receivers']['hosts']);?>/></td>
                                </tr><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="receivers-hosts"><?php fa_circle('fa-user ');?> <?php _e('Me (Single Host)', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><input type="checkbox" id="receivers-hosts" name="jte_bulk_mail[receivers][user]" value="<?php echo get_current_user_id();?>" <?php checked(1, $options['receivers']['me']);?>/></td>
                                </tr></table>
                            </div>
                        </div>
                        <div class="postbox">
                            <h3 class="hndle"><span><?php fa('fa-file-text-o');?> <?php _e('Content', 'jte');?></span></h3>
                            <div class="inside">
                            	<table class="jte-table"><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="mail-subject"><?php fa_circle('fa-font');?> <?php _e('Subject', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><input type="text" id="mail-subject" name="jte_bulk_mail[subject]" value="<?php echo $options['subject'];?>"/></td>
                                </tr><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="mail-text"><?php fa_circle('fa-align-left');?> <?php _e('Text', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element">
										<?php $settings = array(
											'textarea_rows' => 15,
											'textarea_name' => 'jte_bulk_mail[text]'
										);
                                        wp_editor( $options['text'], 'mail-text', $settings ); ?> 
                                    </td>
                                </tr></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="side-info-column" class="inner-sidebar">
            	<div class="postbox">
                	<h3 class="hndle"><span><?php _e('Send Mails', 'jte');?></span></h3>
                    <div class="inside">
                    	<p><?php _e('Please check all your data before sending the mails.', 'jte');?></p>
                    	<?php submit_button(__('Send Bulk Mail', 'jte'));?>
                    </div>
                </div>
            </div>
        </div>
		</form>
<?php } ?>