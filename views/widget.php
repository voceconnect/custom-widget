<?php 
if ( $title ) {
    echo $before_title . $title . $after_title;
}
?>
<ul>
	<li>
		<?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?>
		<?php echo wpautop($text); ?>
		<a href="<?php echo $cta_url; ?>"><?php echo $cta_text; ?></a>
	</li>    

</ul>
