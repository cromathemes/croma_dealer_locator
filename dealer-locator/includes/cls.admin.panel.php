<?php
/**
 * Mediaone maps Admin Panel.
 *
 * @link              http://ava.to
 * @since             0.1
 */
if (!defined('WPINC')) {
    die;
}

if (!class_exists('Mediaone_admin')) :

    class Mediaone_admin
    {

        private $dashboard_array = array(
            'media_recipient', 'agent_id','agent_uname','agent_pwd'
        );


        // constructor to create the admin page
        public function __construct()
        {
            add_action('admin_menu', array($this, 'setup_admin_panel') );
						add_action('admin_init', array($this, 'save_admin_panel'));
            add_action('admin_enqueue_scripts', array($this, 'dashboard_scripts'));
        }

        /**
         * Add options page.
         */
        public function setup_admin_panel()
        {
            add_menu_page(
                'Mediaone Maps',
                'Mediaone maps',
                'manage_options',
                'mediaone-dash',
                array($this, 'create_mediaone_dash')
            );
        }

        /**
         * option page callback.
         */
        public function create_mediaone_dash()
        {

						$roo = $this->mediaone_dashvalues('dashboard');

            ob_start();

            include get_stylesheet_directory().'/includes/html/html.admin.dashboard.php';
            $page = ob_get_contents();
            ob_end_clean();

            echo $page;

            return;
        }

				/**
				 * Save admin options
				 */
				public function save_admin_panel(){

					// if the dashboard was not saved, return;
					if ( isset( $_POST['mediaone_main_dash'] ) &&  $_POST['mediaone_main_dash'] != '1' ) return;

					// if the nonce field is not set return
					if ( !isset($_POST['mediaone-nonce-field'])) return;

					// if the nonce does not check out, close down everything;
					if ( ! wp_verify_nonce( $_POST['mediaone-nonce-field'], 'mediaone-nonce-action' ) ) {
						die( 'Security check' );
					}

					$dashoption = get_option('mediaone_values');

					foreach ($this->dashboard_array as $item){
						$dashoption[$item] = (isset($_POST[$item]))? esc_attr($_POST[$item]) : '' ;
					}

					update_option('mediaone_values', $dashoption);

					wp_redirect( admin_url( 'admin.php?page=mediaone-dash&mediaone-update=true' ) );

				}


				/**
				 * Enqueue scripts and styles
				 */
        public function dashboard_scripts($hook)
        {

          // enqueue scripts and styles only for the roosterly dash
          if ($hook != 'toplevel_page_mediaone-dash') {
              return;
          }

            wp_dequeue_script( 'default' );

            wp_enqueue_script('mediaone_dash', get_stylesheet_directory_uri() .'/assets/js/mediaone_app.js');


            wp_enqueue_style('mediaone-styles', get_stylesheet_directory_uri() .'/assets/css/admin_style.css',  array(), false, 'all');

            wp_enqueue_style('mediaone-fonts', '//fonts.googleapis.com/css?family=Lato:400,100,300,700,900',  array(), false, 'all');
        }



				/**
				 * Return the values for a dashtype
				 */
        public function mediaone_dashvalues($dashtype)
        {

					$dashoption = get_option('mediaone_values');
					$return     = array();

					switch ($dashtype) {
						case 'dashboard':

							foreach ($this->dashboard_array as $item ){


								$return[$item] = ( isset( $dashoption[$item] ) )? $dashoption[$item] : ''  ;

							}

						break;

					}

					return $return;

        }
    }

endif;

new Mediaone_admin();
