<?php function jte_settings_page() { ?>
	<div class="wrap">
		<h2><?php _e('Join the Event (Settings)', 'jte');?></h2>
        <?php settings_errors();?>
        <h2 class="nav-tab-wrapper">
            <a href="#" class="nav-tab nav-tab-active"><?php fa('fa-cog');?> <?php _e('Settings', 'jte');?></a>
            <a href="?page=jte_bulk_mail" class="nav-tab"><?php fa('fa-envelope-o');?> <?php _e('Bulk Mail', 'jte');?></a>
        </h2>
		<form action="options.php" method="post" id="post">
        <?php settings_fields('jte_settings_group');
		$options = get_option('jte_settings');?>
		<div id="poststuff" class="metabox has-right-sidebar">   
            <div id="post-body">
                <div id="post-body-content">
                    <div id="normal-sortables" class="meta-box">
                        <div class="postbox">
                            <h3 class="hndle"><span><?php fa('fa-file-text-o');?> <?php _e('Form Texts', 'jte');?></span></h3>
                            <div class="inside">
                            	<table class="jte-table"><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="form-response"><?php fa_circle('fa-undo');?> <?php _e('Response Text', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><textarea id="form-response" name="jte_settings[form-texts][response]"><?php echo $options['form-texts']['response'];?></textarea></td>
                                </tr><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="form-error"><?php fa_circle('fa-exclamation ');?> <?php _e('Error Message', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><textarea id="form-error" name="jte_settings[form-texts][error]"><?php echo $options['form-texts']['error'];?></textarea></td>
                                </tr><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="form-deadline"><?php fa_circle('fa-times');?> <?php _e('Deadline Text', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><textarea id="form-deadline" name="jte_settings[form-texts][deadline]"><?php echo $options['form-texts']['deadline'];?></textarea></td>
                                </tr></table>
                            </div>
                        </div>
                        <div id="confirmation_mail" class="postbox">
                            <h3 class="hndle"><span><?php fa('fa-envelope-o');?> <?php _e('Registration Mail', 'jte');?></span></h3>
                            <div class="inside">
                            	<table class="jte-table"><tr class="jte-row">
                                	<td><h3><?php _e('Hosts', 'jte');?></h3></td>
                                </tr><tr class="jte-row">
                                	<td class="jte-cell jte-label-cell"><label for="confirmation"><?php fa_circle('fa-users');?> <?php _e('New Guest', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element">
                                    	<input id="confirmation" type="checkbox" name="jte_settings[email][confirmation]" value="1" <?php checked(1, $options['email']['confirmation']);?>/> <label for="confirmation">
										<?php _e("Every user receives a confirmation mail when a new guest is confirmed", 'jte');?> </label>
                                        <?php if (get_user_meta(get_current_user_id(), 'jte_receivemail', true) !== "1") { ?>
                                        <p class="description"><?php _e('<b>Currently you will not receive a confirmation mail.</b> You can change this on your Profile:', 'jte');?> <a href="<?php echo admin_url('profile.php');?>"><?php _e('Your Profile', 'jte');?></a></p>
                                        <?php } ;?></td>
                                </tr><tr class="jte-row">
                                	<td><h3><?php _e('Guests', 'jte');?></h3></td>
                                </tr><tr class="jte-row">
                                    <td class="jte-cell jte-label-cell"><label for="subject"><?php fa_circle('fa-font');?> <?php _e('Subject', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element"><input id="subject" type="text" name="jte_settings[email][subject]" value="<?php echo $options['email']['subject'];?>"/></td>
                                </tr><tr class="jte-row">
                                	<td class="jte-cell jte-label-cell"><label for="text"><?php fa_circle('fa-align-left');?> <?php _e('Text', 'jte');?></label></td>
                                    <td class="jte-cell jte-form-element">
                                    	<?php _e("<p>You can use the following shortcodes:</p><p><code>[firstname]</code> to embed the firstname<br/><code>[surname]</code> to embed the surname<br/><code>[plus]</code> to embed the amount of escorts
                                        </p>", 'jte');?>
                                        <?php $settings = array(
											'textarea_rows' => 15,
											'textarea_name' => 'jte_settings[email][text]'
										);
                                        wp_editor($options['email']['text'], 'mail-text', $settings); ?> 
                                	</td>
                                </tr></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="side-info-column" class="inner-sidebar">
            	<div class="postbox">
                	<h3 class="hndle"><span><?php _e('Save Settings', 'jte');?></span></h3>
                    <div class="inside">
                    	<?php submit_button(__('Save', 'jte'));?>
                    </div>
                </div>
                <div class="postbox">
					<h3 class="hndle"><span><?php fa('fa-calendar-o');?> <?php _e('Event Settings', 'jte');?></span></h3>
                    	<div class="inside">
							<table class="jte-table"><tr class="jte-row">
								<td class="jte-cell jte-form-element"><input id="event-status" type="checkbox" name="jte_settings[event][status]" value="1" <?php checked(1, $options['event']['status']);?>/><label for="event-status"><?php _e('Activate RVSP Form', 'jte');?></label></td>
							</tr><tr class="jte-row">
								<td class="jte-cell jte-label-cell"><label for="event-date"><?php fa_circle('fa-calendar-o');?> <?php _e('Date', 'jte');?></label></td>
								<td class="jte-cell jte-form-element"><input id="event-date" class="has-datepicker" type="date" name="jte_settings[event][date]" value="<?php echo $options['event']['date'];?>" required/></td>
							</tr><tr class="jte-row">
								<td class="jte-cell jte-label-cell"><label for="event-plus"><?php fa_circle('fa-users');?> <?php _e('Max. Escorts', 'jte');?></label></td>
								<td class="jte-cell jte-form-element"><input id="event-plus" class="" type="number" size="1" name="jte_settings[event][plus]" value="<?php echo $options['event']['plus'];?>" required/></td>
							</tr><tr class="jte-row">
                                <td class="jte-cell jte-label-cell"><label for="event-facebook"><?php fa_circle('fa-facebook');?> <?php _e('Facebook Page/Event Link', 'jte');?></label></td>
								<td class="jte-cell jte-form-element"><input id="event-facebook" type="url" name="jte_settings[event][facebook]" value="<?php echo $options['event']['facebook'];?>"/></td>
                            </tr><tr class="jte-row">
                              	<td class="jte-cell jte-label-cell"><label for="event-ics"><?php fa_circle('fa-file-o');?> <?php _e('ICS-File', 'jte');?></label></td>
                                <td class="jte-cell jte-form-element">
									<span class="file-output"><?php if ($options['event']['ics-name']) { echo $options['event']['ics-name']; } else { _e('No file found.', 'jte'); }?></span>
                                    <input id="event-ics" class="file-input-url" type="hidden" name="jte_settings[event][ics-url]" value="<?php echo $options['event']['ics-url'];?>"/>
                                    <input class="file-input-name" type="hidden" name="jte_settings[event][ics-name]" value="<?php echo $options['event']['ics-name'];?>"/>
                                    <a href="#" class="button add-file"><?php fa('fa-arrow-circle-o-up');?> <?php _e('Upload File', 'jte');?></a>
                                    <?php if ($options['event']['ics-url']):?><a href="#" class="button remove-file"><?php fa('fa-trash-o');?> <?php _e('Remove File', 'jte');?></a><?php endif;?>
                                </td>
                                </tr>
                            </table>
						</div>
					</div>
            </div>
        </div>
		</form>
    </div>
	<?php
}

function jte_settings_valid($input) {
	return $input;
}

?>