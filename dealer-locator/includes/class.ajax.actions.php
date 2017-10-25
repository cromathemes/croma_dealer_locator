<?php
/**
 * Convert ajax functions
 *
 * @since             1.0
 * @package           ConvertIX
 *
 *
 */
if ( ! defined( 'WPINC' ) ) die;



if ( ! class_exists( 'Mediaone_Ajax' ) ) :

	class Mediaone_Ajax{


		/**
	    * Hook in ajax handlers
	    */
	   public static function init() {
	   		add_action( 'init', array( __CLASS__, 'add_ajax_events'), 0 );
	   		add_action( 'template_redirect', array( __CLASS__, 'do_mediaone_ajax'), 0 );
	   }



		/**
		* Hook in methods - uses WordPress ajax handlers (admin-ajax)
		*/
		public static function add_ajax_events() {

			$ajax_events = array(
				'addtomap'    => true,
				'deletemap'   => true,
				'getlat'      => true,
				'importcsv'   => true
			);


			foreach ( $ajax_events as $ajax_event => $nopriv ) {
				add_action( 'wp_ajax_mediaone_' . $ajax_event, array( __CLASS__, $ajax_event ) );
				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_mediaone_' . $ajax_event, array( __CLASS__, $ajax_event ) );
				}
			}


		}



		/**
	    * Check for Cromax Ajax request and fire action
	    */
		public static function do_mediaone_ajax() {
			global $wp_query;


			if ( ! empty( $_GET['mediaone-ajax'] ) ) {
				$wp_query->set( 'mediaone-ajax', sanitize_text_field( $_GET['mediaone-ajax'] ) );
			}



			if ( $action = $wp_query->get( 'mediaone-ajax' ) ) {

				if ( ! defined( 'DOING_AJAX' ) ) {
					define( 'DOING_AJAX', true );
				}

				if ( ! defined( 'CONVERT_DOING_AJAX' ) ) {
					define( 'CONVERT_DOING_AJAX', true );
				}

				do_action( 'mediaone_ajax_' . sanitize_text_field( $action ) );
				die();
			}
		}



		public static function deletemap(){

			$array   = get_option( 'mediaone_' . $_GET['types'] );

			$id      = $_GET['id'];

			$add_arr = array();

			foreach ($array as $value){
				if ( $id != $value['m-id'] ){
					$add_arr[] = $value;
				}
			}

			update_option( 'mediaone_' . $_GET['types'], $add_arr );

			wp_die();

		}


		public static function getlat(){

		  $address 		= urlencode( $_GET['addr'] );

		  $url 				= "http://maps.google.com/maps/api/geocode/json?address={$address}";

		  $curl       = curl_init($url);
		  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		  $response   = curl_exec( $curl );
		  curl_close( $curl );

		  $resp 	  = json_decode($response, true);

			$lati       = '';

			$longi      = '';

			$op         = '0';

			if($resp['status']=='OK'){

				$lati = $resp['results'][0]['geometry']['location']['lat'];
                $longi = $resp['results'][0]['geometry']['location']['lng'];

			}

			if ($lati . $longi != ''){
				$op = $lati . '||' . $longi;
			}

			if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) ){
				$ctr = 0;

				$array   = get_option( 'mediaone_' . $_GET['types'] );

				$id      = $_GET['id'];


				foreach ($array as $value){
					if ( $id == $value['m-id'] ){
						$array[$ctr]['lat'] = $lati;
						$array[$ctr]['lng'] = $longi;
					}
					$ctr++;
				}

				update_option( 'mediaone_' . $_GET['types'], $array );

			}

			echo $op;

			wp_die();

		}


		public static function addtomap() {


			$array   = get_option( 'mediaone_' . $_GET['types'] );

			if($array == ''){
				$array = array();
			}


			$updated = 0;


			$id      = $_GET['m-id'];

			$add_arr = array();

			$newarr  = array(
				'm-id'       => $id,
				'name'       => ( isset( $_GET['name'] ) &&  $_GET['name'] != '' )? $_GET['name'] : '' ,
				'address'    => ( isset( $_GET['address'] ) &&  $_GET['address'] != '' )? $_GET['address'] : '' ,
				'city'       => ( isset( $_GET['city'] ) &&  $_GET['city'] != '' )? $_GET['city'] : '' ,
				'state'      => ( isset( $_GET['state'] ) &&  $_GET['state'] != '' )? $_GET['state'] : '' ,
				'country'    => ( isset( $_GET['country'] ) &&  $_GET['country'] != '' )? $_GET['country'] : '' ,
				'zip'        => ( isset( $_GET['zip'] ) &&  $_GET['zip'] != '' )? $_GET['zip'] : '' ,
				'tel'        => ( isset( $_GET['tel'] ) &&  $_GET['tel'] != '' )? $_GET['tel'] : '' ,
				'mail'       => ( isset( $_GET['mail'] ) &&  $_GET['mail'] != '' )? $_GET['mail'] : '' ,
				'web'        => ( isset( $_GET['web'] ) &&  $_GET['web'] != '' )? $_GET['web'] : '' ,
				'lat'        => ( isset( $_GET['lat'] ) &&  $_GET['lat'] != '' )? $_GET['lat'] : '' ,
				'lng'        => ( isset( $_GET['lng'] ) &&  $_GET['lng'] != '' )? $_GET['lng'] : ''
			);


			foreach($array as $value){
				if ( $id == $value['m-id'] ){
					$updated = 1;
					$add_arr[] = $newarr;
				} else {
					$add_arr[] = $value;
				}
			}

			if ( $updated == 0 ){

				$add_arr[] = $newarr;

			}

			update_option('mediaone_' . $_GET['types'], $add_arr);


			wp_die();

		}


		public static function importcsv() {

			if ( !file_exists( get_stylesheet_directory() . '/import.csv' ) ) {
				echo 'There was an error';
				wp_die();
			}

			$add_arr    = get_option( 'mediaone_dealers' );
			$medctr     = 1;
			$medictr    = 0;

			if ( is_array( $add_arr ) ){
				foreach ( $add_arr as $arr ){
					
					$add_arr[$medictr]['m-id'] = $medctr;
					
					$medctr++;
					$medictr++;
				}
			} else {
				$add_arr = [];
			}

			


			ob_start();

			include(get_stylesheet_directory() . '/import.csv');

			$csv = ob_get_contents();


			ob_end_clean();


			$csv = str_getcsv($csv, "\n");


			foreach ( $csv as $v ) {
			  $single = str_getcsv($v, ",");


			  $newarr = array(
				'm-id'       => $medctr,
				'name'       => ( isset( $single[0] )  &&  $single[0] != ''  && $single[0] != 'NULL' )?   $single[0] : '' ,
				'address'    => ( isset( $single[1] )  &&  $single[1] != ''  && $single[1] != 'NULL'  )?  $single[1] : '' ,
				'city'       => ( isset( $single[2] )  &&  $single[2] != ''  && $single[2] != 'NULL'  )?  $single[2] : '' ,
				'state'      => ( isset( $single[3] )  &&  $single[3] != ''  && $single[3] != 'NULL'  )?  $single[3] : '' ,
				'country'    => ( isset( $single[4] )  &&  $single[4] != ''  && $single[4] != 'NULL'  )?  $single[4] : '' ,
				'zip'        => ( isset( $single[5] )  &&  $single[5] != ''  && $single[5] != 'NULL'  )?  $single[5] : '' ,
				'tel'        => ( isset( $single[6] )  &&  $single[6] != ''  && $single[6] != 'NULL'  )?  $single[6] : '' ,
				'mail'       => ( isset( $single[7] )  &&  $single[7] != ''  && $single[7] != 'NULL'  )?  $single[7] : '' ,
				'web'        => ( isset( $single[8] )  &&  $single[8] != ''  && $single[8] != 'NULL'  )?  $single[8] : '',
				'lat'        => ( isset( $single[9] )  &&  $single[9] != ''  && $single[9] != 'NULL'  )?  $single[9] : '' ,
				'lng'        => ( isset( $single[10] ) &&  $single[10] != '' && $single[10] != 'NULL'  )? $single[10] : ''
			  );

			  array_push( $add_arr, $newarr );

			  $medctr++;

			}
			

			update_option('mediaone_dealers',$add_arr);

			wp_die();

		}




	}

endif; // End if class_exists check

Mediaone_Ajax::Init();
