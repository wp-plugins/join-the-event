<?php 
$options = get_option('jte_settings'); 
if($options['event']['status'] == 1) { 
	if(time() <= strtotime($options['event']['date'] . ' 23:59')) { ?>
	<div id="join-the-event">
        <form id="jte-form" method="post" novalidate="novalidate">
        	<ul><li>
            <b><?php _e('Personal Information', 'jte');?></b>
            </li><li>
        	<label for="firstname"><?php fa('fa-user', true);?></label>
            <input id="firstname" type="text" name="firstname" placeholder="<?php _e('First Name', 'jte');?>" data-required="true"/>
            </li><li>
        	<label for="surname"><?php fa('fa-user', true);?></label>
            <input id="surname" type="text" name="surname" placeholder="<?php _e('Surname', 'jte');?>" data-required="true"/>
        	</li><li>
            <label for="mail"><?php fa('fa-envelope-o', true);?></label>
            <input id="mail" type="email" name="mail" placeholder="<?php _e('Mail Address', 'jte');?>" data-required="true"/>
        	</li><li>
            <b><?php _e('Number of Escorts', 'jte'); echo " (max. " . $options['event']['plus'] . ")";?></b>
            </li><li>
            <label for="plus"><?php fa('fa-user-plus', true);?></label>
            <input id="plus" type="number" size="1" placeholder="0" min="0" max="<?php echo $options['event']['plus'];?>" name="plus" data-required="false" />
            </li><li class="form-submit">
            <?php wp_nonce_field('jte_nonce') ?>
			<label for="submit"><?php fa('fa-bookmark-o', true);?></label>
            <input type="submit" id="submit-jte-form" value="<?php _e('Submit', 'jte');?>"></input>
            </li></ul>
        </form>
        <div class="spinner">
        	<div class="bounce-wrapper">
                <span class="bounce1"></span>
                <span class="bounce2"></span>
                <span class="bounce3"></span>
            </div>
        </div>
     </div>
<?php } else { ?>
	<div id="jte_wrapper">
		<?php fa_circle('fa-bookmark-o', true); ?>
		<p><?php echo $options['form-texts']['deadline'];?></p>
	</div>
<?php } } ?>