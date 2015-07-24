<?php if (!is_user_logged_in()) { wp_redirect(home_url()); exit; }?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php bloginfo('name');?> | <?php _e('Guestlist', 'jte');?></title>
	<?php wp_head();?>
</head>
<body>
<article class="wrapper">
    <h1><?php fa('fa-bookmark-o', true);?> <?php _e('Guestlist', 'jte');?></h1>
    <?php while(have_posts()):the_post();?>
        <table id="guestlist">
            <thead>
                <th><?php _e('Nr.', 'jte');?></th>
                <th class="check"><?php fa('fa-check', true);?></th>
                <th><?php _e('Surname', 'jte');?></th>
                <th><?php _e('First Name', 'jte');?></th>
                <th><?php _e('Plus', 'jte');?></th>
                <th><?php _e('Mail Address', 'jte');?></th>
            </thead>
        <?php $args = array(
            'post_type' => 'guest',
            'posts_per_page' => -1,
            'meta_key' => '_jte_guest_surname',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'cache_results' => false,
        );
        $the_query = new WP_Query( $args );
        $i = 1;
        while ($the_query->have_posts()):$the_query->the_post();?>
            <tr>
                <td><?php echo $i; $id = get_the_ID()?></td>
                <td class="check"><?php fa('fa-square-o', true);?></i></td>
                <td class="surname"><?php echo get_post_meta($id, '_jte_guest_surname', true);?></td>
                <td class="firstname"><?php echo get_post_meta($id, '_jte_guest_firstname', true);?></td>
                <td><?php $begleitung = get_post_meta($id, '_jte_guest_escort', true); if (empty($begleitung)) { echo "0"; } else { echo $begleitung; }?></td>
                <td class="mail"><a href="mailto:<?php echo get_post_meta($id, '_jte_guest_mail', true);?>"><?php echo get_post_meta($id, '_jte_guest_mail', true);?></a></td>
            </tr>
        <?php $i++; endwhile; wp_reset_postdata();?>
        </table>
    <?php endwhile;?>
</article>
</body>
<footer>
</footer>
</html>