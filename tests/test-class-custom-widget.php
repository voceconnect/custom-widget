<?php

/**
 * Tests to test that that testing framework is testing tests. Meta, huh?
 *
 * @package wordpress-plugins-tests
 */
class Test_Custom_Widget extends Voce_WP_UnitTestCase {

    /**
     * If these tests are being run on Travis CI, verify that the version of
     * WordPress installed is the version that we requested.
     *
     * @requires PHP 5.3
     */
    function test_wp_version() {

        if ( ! getenv( 'TRAVIS' ) ) {
            $this->markTestSkipped( 'Test skipped since Travis CI was not detected.' );
        }

        $requested_version = getenv( 'WP_VERSION' );

        // The "latest" version requires special handling.
        if ( 'latest' === $requested_version ) {

            $file = file_get_contents( ABSPATH . WPINC . '/version.php' );
            preg_match( '#\$wp_version = \'([^\']+)\';#', $file, $matches );
            $requested_version = $matches[1];

        }

        $this->assertEquals( get_bloginfo( 'version' ), $requested_version );

    }

    /**
     * Ensure that the plugin has been installed and activated.
     */
    function test_plugin_activated() {

        $this->assertTrue( is_plugin_active( 'custom-widget/custom-widget.php' ) );

    }

    function provider_test_init() {

        return array(
            array( 'widgets_init', 'register_widget' ),
            array( 'admin_print_scripts-widgets.php', 'enqueue' )
        );
    }

    /**
     * @covers Custom_Widget::init
     * @dataProvider provider_test_init
     */
    function test_init( $hook, $function, $expected = 10 ) {

        $custom_widget = new Custom_Widget;
        $custom_widget->init();
        $actual = has_action( $hook, array( $custom_widget, $function ) );
        $this->assertEquals( $actual, $expected );

    }

    /**
     * @covers Custom_Widget::register_widget
     */
    function test_register_widget(){
        $custom_widget = new Custom_Widget;
        $custom_widget->register_widget();
        $widgets = array_keys( $GLOBALS['wp_widget_factory']->widgets );

        $this->assertContains( 'Custom_Widget', $widgets );

    }

    /**
     * @covers Custom_Widget::widget
     */
    function test_widget(){

        $args = array('before_title' => 'foo', 'before_widget' => 'bar', 'after_widget'=> 'baz' );
        $cta_text = 'call to action text';
        $cta_url = 'http://google.com';
        $instance = array(

            'title' => 'title',
            'cta_url' => $cta_url,
            'cta_text' => $cta_text,
            'attachment_id' => 'attachment_id',
            'text' => 'this is text'

        );

        $custom_widget = new Custom_Widget;
        ob_start();
        $custom_widget->widget( $args, $instance );
        $output = ob_get_clean();

		$document = new DOMDocument;
		$document->preserveWhiteSpace = false;
		$document->loadHTML( $output );
		$xpath = new DOMXPath ( $document );
		$anchor_tag = $xpath->query("//a[@href='" . $cta_url . "']");

		$contents = $anchor_tag->item(0)->nodeValue;

		$this->assertEquals( $contents, $cta_text );
		$this->assertEquals( 1, $anchor_tag->length );




    }

    function provider_test_update(){

        return array(

            array(
                array(

                    'title' => 'foo',
                    'cta_url' => 'google.com',
                    'cta_text' => 'bar',
                    'text' => 'fozbar',
                    'attachment_id' => 'bar'

                ),

                array(

                    'title' => 'foo',
                    'cta_url' => 'http://google.com',
                    'cta_text' => 'bar',
                    'text' => 'fozbar',
                    'attachment_id' => 0

                )

            ),

            array(
                array(

                    'title' => 'bazbar<scary style="muhahaha" href="http://evil.com">foo</scary>',
                    'cta_url' => 'http://yahoo.com',
                    'cta_text' => 'bar<removethis></removethis>',
                    'text' => 'fozbar<iamevil>bar</iamevil>',
                    'attachment_id' => 123

                ),

                array(

                    'title' => 'bazbarfoo',
                    'cta_url' => 'http://yahoo.com',
                    'cta_text' => 'bar',
                    'text' => 'fozbarbar',
                    'attachment_id' => 123

                )

            ),



        );


    }


    /**
     * @covers Custom_Widget::update
     * @dataProvider provider_test_update
     */
    function test_update( $new_instance, $expected ){

        $custom_widget = new Custom_Widget;
        $actual = $custom_widget->update( $new_instance, 'foo' );
        $this->assertEquals( $expected, $actual );

    }

    function provider_test_form(){

        return array(

            array( array( 'attachment_id' => 123 ), array( 'foo' ), 1 )

        );

    }


    /**
     * @covers Custom_Widget::form
     * @dataProvider provider_test_form
     */
    function test_form( $instance, $image_array, $wp_get_attachment_image_src_expects ){



        $custom_widget = $this->getMock( 'Custom_Widget', array( 'get_field_id', 'get_field_name', 'wp_get_attachment_image_src' ) );

        $custom_widget->expects( $this->exactly( $wp_get_attachment_image_src_expects ) )
            ->method( 'wp_get_attachment_image_src' )
            ->will ( $this->returnValue( $image_array ) );

		/*
		 * DOMDocument::loadHTML() returns an error if HTML is not valid (e.g. two ids with same value), let's have the
		 * Mocked methods Custom_Widget::get_field_id() and Custom_Widget::get_field_name() return what is passed to
		 * them to preserve the HTML's validity
		 */


        $custom_widget->expects( $this->exactly( 9 ) )
            ->method( 'get_field_id' )
			->will( $this->returnCallback( function(){
				$args = func_get_args();
				return $args[0];
			}));



        $custom_widget->expects( $this->at( 0 ) )
            ->method( 'get_field_name' )
			->will( $this->returnCallback( function($x){
				$args = func_get_args();
				return $args[0];
			}));

        ob_start();

        $custom_widget->form( $instance );
        $output = ob_get_clean();
		$document = new DOMDocument;
		$document->preserveWhiteSpace = false;
		$document->loadHTML( $output );
		$xpath = new DOMXPath ( $document );
		$img_tag = $xpath->query("//img[@src='" . $image_array[0] . "']");
		$this->assertEquals( 1, $img_tag->length );


    }

    /**
     * @covers Custom_Widget::enqueue
     */

    function test_enqueue(){

        // set to an admin screen

        set_current_screen( 'post-new.php' );
        $custom_widget = new Custom_Widget;
        $custom_widget->enqueue();
        $this->assertTrue( wp_script_is( 'cw-admin' ) );
        $this->assertTrue( wp_style_is( 'cw-admin' ) );


    }


}
