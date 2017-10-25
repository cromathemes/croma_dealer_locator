<?php


function maps_shortcode($atts){

    $atts = shortcode_atts( array(
      'url' => '',
    ), $atts, 'm1_dealer_locator' );


  wp_enqueue_script( 'm1-store-locator', '//maps.googleapis.com/maps/api/js?key=AIzaSyBykZDXIFt0A3aMn8G-pgKRxQX8ZaB7p88&libraries=places', array(), false, false );

  wp_enqueue_script( 'm1-store-locator-google-script', get_stylesheet_directory_uri() .  '/assets/js/store-locator.min.js', array('jquery'), false, true );

  wp_enqueue_script( 'm1-store-locator-script', get_stylesheet_directory_uri() .  '/assets/js/panel.js', array('jquery'), false, true );


  wp_enqueue_style( 'm1-store-style', get_stylesheet_directory_uri() . '/assets/css/storelocator.css'  );

  return '
      <script>
          var m1CsvFile = "'  .  $atts['url']   .  '";
      </script>
     <style>
     #m1-panel{
        display: inline-block;
        width: 30%;
        vertical-align: top;
        height:  500px;
     }
     #m1-map-canvas{
        display: inline-block;
        width: 69%;
        vertical-align: top;
        height: 500px;
     }
     @media (max-width: 768px){
      #m1-panel{
        display: inline-block;
        width: 100%;
        vertical-align: top;
        height:  500px;
     }
     #m1-map-canvas{
        display: inline-block;
        width: 100%;
        vertical-align: top;
        height: 500px;
     }
     }
     </style>
     <div class="clear">
        <div id="m1-panel"></div>
        <div id="m1-map-canvas"></div>
     </div>

  ';



}
add_shortcode( 'm1_dealer_locator', 'maps_shortcode' );


  function m1_do_array($string){
    $array = explode(';',$string);

    $ret =  array(

      'number'    => $array[0],
      'name'      => $array[1],
      'address'   => $array[2],
      'city'      => $array[3],
      'state'     => $array[4],
      'country'   => $array[5],
      'zip'       => $array[6],
      'telephone' => $array[7],
      'fax'       => $array[8],
      'mail'      => $array[9],
      'website'   => $array[10],
      'spare1'    => $array[11],
      'spare2'    => $array[12],
      'lat'       => NULL,
      'lng'       => NULL,
      'mal'       => NULL

    );

    return $ret;

  }


function m1_convert_maps(){

  $b   = get_option('m1_map');
  $c   = array();

  foreach ($b as $v ){
      $d = '';

      foreach ( $v as  $t) {
        $d .= $t . ';';
      }

      $d = str_replace('"', '', $d);
      $d = str_replace("\n",'',$d);
      $d = str_replace("\r",'',$d);
      $d = rtrim($d, ";");

      array_push($c, $d );

  }

  print_r($c);

  $file = fopen("scotty.csv","w");
  foreach ($c as $line){
  fputcsv($file,explode(';',$line),';');
  }

  fclose($file);


}


function maps_output() {
  $file    = dirname( get_bloginfo('stylesheet_url') ) . "/scotties.csv";
  $csv     = file_get_contents($file);
  $array   = explode("\n", $csv);
  $d       = 6;
  $e       = 0;
  $cnt     = 0;
  $string  = '';
  $last_arr = array();

  $a = get_option('m1_maps');


  foreach($a as $v){

    if ( $v['lat'] || $v['malformed'] == true ){

      continue;

    } elseif ($cnt == 50 ) {

      break;

    } else {

      $address = urlencode( $v['address'] . ' ' . $v['city'] . ' ' . $v['state'] . ' ' . $v['country']  );

      $url     = "https://maps.google.com/maps/api/geocode/json?address=$address&key=AIzaSyDDgrW5zPga3Z6h5k-IZWCdMLfQOHbeNJI";
      $ch      = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      $response = json_decode(curl_exec($ch), true);
      curl_close($ch);

      if (  $response['results'][0]['geometry']['location']['lat'] ){

        $a[$e]['lat']  = $response['results'][0]['geometry']['location']['lat'];
        $a[$e]['lng']  = $response['results'][0]['geometry']['location']['lng'];

      } else {
        $a[$e]['malformed'] = true;
      }

      $cnt++;

    }

    $e++;

  }

  print_r($a);

  file_put_contents("array.txt",json_encode($a));

  update_option( 'm1_maps', $a );



  if ($d == 4 ) {

    foreach ($array as $v )  {
      // if ($cnt >= 2 ) { continue; }
        $details = explode( ';', $v[0] );
        $address = urlencode( $details[2] . ' ' . $details[3] . ' ' . $details[4] . ' ' . $details[5] );


        $url     = "https://maps.google.com/maps/api/geocode/json?address=$address&key=AIzaSyDDgrW5zPga3Z6h5k-IZWCdMLfQOHbeNJI";
        $ch      = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (  $response['results'][0]['geometry']['location']['lat'] ){

          $string .= $v[0]  . $response['results'][0]['geometry']['location']['lat'] . ';' . $response['results'][0]['geometry']['location']['lng'] . ";\n";

        }

        $cnt++;
      }

      print_r($string);
  }
}
