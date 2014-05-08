<?php

class Custom_Widget extends WP_Widget {

    protected static $text_domain = 'custom_widget';
    protected static $ver = '0.1.2'; //for cache busting
    protected static $transient_limit = 60;

    /**
     * Initialization method
     */
    public static function init() {
	add_action( 'widgets_init', create_function( '', 'register_widget( "Custom_Widget" );' ) );
	add_action( 'admin_print_scripts-widgets.php', array( __CLASS__, 'enqueue' ) );
    }

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
	parent::__construct(
		'custom_widget', // Base ID
		'Custom Widget', // Name
		array( 'description' => __( 'Customized widget allowing you to output a title, text, link and image.', self::$text_domain ), ) // Args
	);
    }

    /**
     * Front-end display of widget.
     *
     * Filter 'cw_template' - template allowing a theme to use its own template file
     * Filter 'cw_{widget_id}_template' - template allowing a theme to use its own template file
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
	$template_file = plugin_dir_path( dirname( __FILE__ ) ) . 'views/widget.php';
	$template_file = apply_filters( 'cw_template', $template_file );
	$template_file = apply_filters( 'cw_' . $this->id . '_template', $template_file );
	$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
	$cta_url = esc_url( $instance['cta_url'] );
	$cta_text = $instance['cta_text'];
	$attachment_id = $instance['attachment_id'];
	$text = $instance['text'];
	?>
	<?php extract( $args ); ?>


	<?php echo $before_widget; ?>
	<?php include( $template_file ); ?>
	<?php echo $after_widget; ?>
	<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = wp_kses_post( $new_instance['title'] );
	$instance['cta_url'] = esc_url( $new_instance['cta_url'] );
	$instance['cta_text'] = wp_kses_post( $new_instance['cta_text'] );
	$instance['text'] = wp_kses_post( $new_instance['text'] );
	$instance['attachment_id'] = ( int ) $new_instance['attachment_id'];
	return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
	$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	$cta_url = isset( $instance['cta_url'] ) ? esc_url( $cta_url ) : '';
	$cta_text = isset( $instance['cta_text'] ) ? esc_attr( $instance['cta_text'] ) : '';
	$text = isset( $instance['text'] ) ? esc_attr( $instance['text'] ) : '';
	$attachment_id = $instance['attachment_id'] ? absint( $instance['attachment_id'] ) : '';
	$image = '<img>';
	if ( $attachment_id ) {
	    $image = '<img src="' . wp_get_attachment_image_src( $attachment_id, 'full' )[0] . '">';
	}
	?>
	<div class="cw-form">
	    <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', self::$text_domain ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'cta_text' ); ?>"><?php _e( 'CTA Text:', self::$text_domain ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" type="text" value="<?php echo $cta_text; ?>" />
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'cta_url' ); ?>"><?php _e( 'CTA URL:', self::$text_domain ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'cta_url' ); ?>" name="<?php echo $this->get_field_name( 'cta_url' ); ?>" type="text" value="<?php echo $cta_url; ?>" />
	    </p>                
	    <p>
		<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:', self::$text_domain ); ?></label> 
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text"><?php echo $text; ?></textarea>
	    </p>                                
	    <div class="image-preview"><?php echo $image; ?></div>
	    <input class="cw-image" id="<?php echo $this->get_field_id( 'attachment_id' ); ?>" name="<?php echo $this->get_field_name( 'attachment_id' ); ?>" type="hidden" value="<?php echo $attachment_id; ?>" />
	    <p>
		<span data-uploader-button-text="Attach to this widget" data-uploader-title="Select the image for the custom widget" class="button-secondary cw-attach-image">Attach image</span>
	    </p>
	</div>
	<?php
    }

    /**
     * Enqueue CSS and JavaScripts
     */
    public static function enqueue() {
	if ( is_admin() ) {
	    wp_enqueue_media();
	    wp_enqueue_style( 'cw-admin', plugins_url( 'css/' . 'cw-admin.min.css', dirname( __FILE__ ) ), false, self::$ver );
	    wp_enqueue_script( 'cw-admin', plugins_url( 'js/' . 'cw-admin.min.js', dirname( __FILE__ ) ), array( 'jquery', 'custom-header' ), self::$ver, true );
	    wp_localize_script( 'cw-admin', 'cwAjax', array(
		'cwNonce' => wp_create_nonce( 'nonce_cw' ),
		    )
	    );
	}
    }

}
