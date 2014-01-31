<?php

class Cw_helper {
    public static function post_types(){
        $recent_post_types = get_post_types( array( '_builtin'=> false ) );
        $recent_post_types[] = 'post';
        $recent_post_types[] = 'page';
        $recent_post_types = apply_filters( 'cw_post_types', $recent_post_types );
        return $recent_post_types;
    }

    public static function get_taxonomies( $post_type, $selected = null ){
        $output = '<option value=""> -- Choose a Taxonomy -- </option>';
        $taxonomies = get_object_taxonomies( $post_type );
        $taxonomies = apply_filters( 'cw_taxonomies', $taxonomies );
        foreach( $taxonomies as $taxonomy ){
            $selected_option = '';
            if ( $selected !== null ) {
                $selected_option = selected( $taxonomy, $selected, false );
            }

            $output .= '<option value="' . $taxonomy . '" ' . $selected_option . '>' . $taxonomy . '</option>';
        }
        return $output;
    }

    public static function get_terms( $term, $selected = null ){
        $output = '<option value=""> -- Choose a Term -- </option>';
        $terms = get_terms( $term );
        foreach( $terms as $term ){
            $selected_option = '';
            $id = $term->slug;
            $name = $term->name;
            if ( $selected !== null ) {
                $selected_option = selected( $id, $selected, false );
            }
            $output .= '<option value="' . $id . '" ' . $selected_option . '>' . $name . '</option>';
        }
        return $output;
    }

    public static function get_post_types( $selected = null ){
        $output = '<option value=""> -- Choose a Post Type -- </option>';
        foreach( Cw_helper::post_types() as $post_type ){
            $selected_output = '';
            if ( $selected !== null ) {
                $selected_output = selected( $post_type, $selected, false );
            }
            $output .= '<option ' . $selected_output . 'value=' . $post_type . '>' . $post_type . '</option>';
        }
        return $output;
    }
}


