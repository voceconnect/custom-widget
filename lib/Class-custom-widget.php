<?php

class Custom_widget extends WP_Widget {

    protected static $text_domain = 'custom_widget';
    protected static $ver = '0.1.0'; //for cache busting
    protected static $transient_limit = 60;
    
    /**
     * Initialization method
     */
    public static function init(){
        add_action( 'widgets_init', create_function( '', 'register_widget( "Custom_widget" );' ) );
        add_action( 'admin_print_scripts-widgets.php', array( __CLASS__, 'enqueue' ) );
    }

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'custom_widget', // Base ID
            'Custom Widget', // Name
            array( 'description' => __( 'Custom widgets!', self::$text_domain ), ) // Args
        );
    }


    /**
     * Front-end display of widget.
     *
     * Filter 'cw_template' - template allowing a theme to use its own template file
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        
        $template_file = apply_filters( 'cw_template', plugin_dir_path( dirname( __FILE__ ) ) . 'views/widget.php' );
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
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
        $instance['title'] = esc_attr( $new_instance['title'] );
        $instance['widget-data'] = esc_attr( $new_instance['widget-data'] );
        
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
        $defaults = array(
            'widget-data' => '',
            'title' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );     
        if ( !isset( $instance[ 'title' ] ) ) {
            $instance['title'] = __( 'Posts', self::$text_domain );
        }
        ?>
        <div class="cw-form">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', self::$text_domain ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
            </p>
            <div class="cw-inner-form">
                <strong>Add new custom block</strong>
                <p class="cw-error"></p>
                <p>
                    <label for="cw-instance-title cw-innerform-text"><?php _e( 'Instance title:', self::$text_domain ); ?></label>
                    <input class="widefat cw-instance-title" name="search" type="text" placeholder="" />
                </p>
                <p>
                    <label for="cw-instance-url cw-innerform-text"><?php _e( 'Instance URL:', self::$text_domain ); ?></label>
                    <input class="widefat cw-instance-url" name="search" type="text" placeholder="" />
                </p>                
                <div class="image-preview"></div>
                <div class="cw-remove-image">[remove Image]</div>
                <p>
                    <span data-uploader-button-text="Attach to this Custom Widget Instance" data-uploader-title="Select the image for the custom widget" class="button-secondary cw-attach-image">Attach image</span>
                    <span class="cw-add-instance button-secondary">Add instance</span>
                </p>
                <input type="hidden" class="cw-current-data">

            </div>
            <input class="widefat cw-widget-data" id="<?php echo $this->get_field_id( 'widget-data' ); ?>" name="<?php echo $this->get_field_name( 'widget-data' ); ?>" type="hidden" value="<?php echo $instance['widget-data']; ?>" />
            <div class="cw-items"></div>
        </div>

        
        <?php

    }


  

    /**
     * Enqueue CSS and JavaScripts
     */
    public static function enqueue(){
        if ( is_admin() ) {
            wp_enqueue_media();
            wp_enqueue_style( 'cw-admin', plugins_url( 'css/' . 'cw-admin.min.css', dirname( __FILE__ ) ), false, self::$ver );
            wp_enqueue_script( 'cw-admin', plugins_url( 'javascripts/' . 'cw-admin.min.js', dirname( __FILE__ ) ), array( 'jquery', 'custom-header' ), self::$ver, true );
            wp_localize_script( 'cw-admin', 'cwAjax', array(
                'cwNonce' => wp_create_nonce( 'nonce_cw' ),
                )
            );
            
        }   
    }

} 
