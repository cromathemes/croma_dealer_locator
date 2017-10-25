<?php
/**
 * Mediaone Realty init
 *
 * @link              http://ava.to
 * @since             0.1
 */
if (!defined('WPINC')) {
    die;
}

if (!class_exists('M1_init')) :

    class M1_init
    {
        // constructor for the class
        public function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'theme_scripts'));
        }



				/**
				 * Enqueue scripts and styles
				 */
        public function theme_scripts()
        {


          wp_register_script( 'mediaone-app', M1_URL.'assets/js/script.js',  array(),  false, true );

          $loc_array = array(
            'url'      => str_replace( 'http:', '', admin_url('admin-ajax.php') ),
            'nonce'    => wp_create_nonce( 'mediaone-app-nonce' )
          );

          wp_localize_script( 'mediaone-app', 'mediaData', $loc_array );

          wp_enqueue_script( 'mediaone-app' );

          wp_enqueue_style( 'mediaone-realty-style', M1_URL.'assets/css/style.css', array(), false, 'all' );

        }



    }

endif;

new M1_init;
