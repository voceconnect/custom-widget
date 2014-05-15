<?php
if ( $title ) {
    echo $before_title . $title . $after_title;
}
?>
<ul>
    <li>
	<?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?>
	<?php echo wp_kses_post( wpautop( $text ) ); ?>
	<a href="<?php echo esc_url( $cta_url ); ?>"><?php echo wp_kses_post( $cta_text ); ?></a>
    </li>    

</ul>
