<?php

class Cw_ajax {
    
    public static function init(){
        add_action( 'wp_ajax_cw_post_type_selected', array( __CLASS__, 'select_taxonomy_callback' ) );
        add_action( 'wp_ajax_cw_taxonomy_selected', array( __CLASS__, 'select_term_callback' ) );
    }

    public static function select_taxonomy_callback(){
        $nonce = $_POST['cwNonce'];
        if ( ! wp_verify_nonce( $nonce, 'nonce_cw' ) ) {
            die;
        }
        $post_type = $_POST['postType'];
        $output = cw_helper::get_taxonomies( $post_type );
        echo $output;
        die;
    }

    public static function select_term_callback(){
        $nonce = $_POST['cwNonce'];
        if ( ! wp_verify_nonce( $nonce, 'nonce_cw' ) ) {
            die;
        }
        $taxonomy = $_POST['taxonomy'];
        $output = cw_helper::get_terms( $taxonomy );
        echo $output;
        die;
    }


}

Cw_ajax::init();