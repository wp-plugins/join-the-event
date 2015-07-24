<?php 
// Add Meta Box
add_action( 'add_meta_boxes', 'jte_meta_box' );
function jte_meta_box() {
	add_meta_box( 'guest-data', __('Guest Data', 'jte'), 'jte_meta_box_content', 'guest', 'normal', 'high' );  
}

function jte_meta_box_content() {
	$values = get_post_custom( $post->ID );?>
<table class="jte-table"><tr class="jte-row">
		<td class="jte-cell jte-label-cell"><label for="firstname"><?php fa_circle('fa-user');?> <?php _e('First Name', 'jte');?></label></td>
		<td class="jte-cell jte-form-element"><input type="text" id="firstname" name="_jte_guest_firstname" value="<?php echo $values['_jte_guest_firstname'][0];?>" required/></td>
	</tr><tr class="jte-row">
		<td class="jte-cell jte-label-cell"><label for="surname"><?php fa_circle('fa-user');?> <?php _e('Surname', 'jte');?></label></td>
		<td class="jte-cell jte-form-element"><input type="text" id="surname" name="_jte_guest_surname" value="<?php echo $values['_jte_guest_surname'][0];?>" required/></td>
	</tr><tr class="jte-row">
		<td class="jte-cell jte-label-cell"><label for="mail"><?php fa_circle('fa-envelope-o');?> <?php _e('Mail Address', 'jte');?></label></td>
		<td class="jte-cell jte-form-element"><input type="text" id="mail" name="_jte_guest_mail" value="<?php echo $values['_jte_guest_mail'][0];?>" /></td>
</tr><tr class="jte-row">
		<td class="jte-cell jte-label-cell"><label for="escort"><?php fa_circle('fa-users');?> <?php _e('Guest plus', 'jte');?></label></td>
		<td class="jte-cell jte-form-element"><input type="number" min="0" id="mail" name="_jte_guest_escort" value="<?php echo $values['_jte_guest_escort'][0];?>" /></td>
</tr></table>
<?php }

// Save Post in Backend
add_action( 'save_post', 'jte_save_post', 10 );
function jte_save_post( $post_id )
{
	$slug = 'guest';
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if( !current_user_can( 'edit_post' ) ) return;
	if( $slug != get_post_type($post) ) return;
	update_post_meta( $post_id, '_jte_guest_firstname', wp_kses( $_POST['_jte_guest_firstname']) );
	update_post_meta( $post_id, '_jte_guest_surname', wp_kses( $_POST['_jte_guest_surname']) );
	update_post_meta( $post_id, '_jte_guest_mail', wp_kses( $_POST['_jte_guest_mail']) );
	update_post_meta( $post_id, '_jte_guest_escort', $_POST['_jte_guest_escort'] );
	$get_title = get_the_title( $post_id );
	if (empty($get_title)) {
		$title = wp_kses( $_POST['_jte_guest_firstname']);
		$title .= " ";
		$title .= wp_kses( $_POST['_jte_guest_surname']);
	} else {
		$title = $get_title;
	}
	remove_action( 'save_post', 'jte_save_post' );
	$post = array(
		'ID' => $post_id,
		'post_title'  => $title,
	);
	wp_update_post( $post );
	add_action( 'save_post', 'jte_save_post', 10 );
}

// Change Admin Columns
function jte_guest_admin_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => fa_circle('fa-user', false),
		'_jte_guest_mail' => fa_circle('fa-envelope-o', false),
		'_jte_guest_escort' => fa_circle('fa-users', false),
		'date' => fa_circle('fa-clock-o', false)
	);
	return $columns;
}
add_filter( 'manage_edit-guest_columns', 'jte_guest_admin_columns' ) ;

function jte_guest_admin_columns_template( $column_name, $post_ID ) {
    if ( $column_name == '_jte_guest_mail' ) {
        $custom_field_values = get_post_meta( $post_ID, '_jte_guest_mail' );
        if (!empty($custom_field_values)) {
            echo $custom_field_values[0];
        }
	}
	if ( $column_name == '_jte_guest_escort' ) {
        $custom_field_values = get_post_meta( $post_ID, '_jte_guest_escort' );
        if (!empty($custom_field_values)) {
			$guests =  $custom_field_values[0] ? ' + ' . $custom_field_values[0] . ' Escorts' : '';
            echo '1 Guest' . $guests;
        }
    }
}
add_action( 'manage_pages_custom_column', 'jte_guest_admin_columns_template', 10, 2) ;

?>