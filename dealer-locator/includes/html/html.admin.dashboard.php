
  <?php


if ( isset( $_GET['type'] ) && $_GET['type'] == 'repairers' ) {

  $m1 = get_option('mediaone_repairers');

} else {

  $m1 = get_option('mediaone_dealers');

}

  if ( isset( $_GET['roo-update'] ) ) {  ?>

  <div class="roo-dash-update-message">
    Dashboard updated
  </div>

  <?php  } ?>


  <?php

  /*

  $csv = '';
  $m1 = array();
  $counter = 1;

  ob_start();

  include('repairers.csv');

  $csv = ob_get_contents();


  ob_end_clean();

  $csv = str_getcsv($csv, "\n");

  print_r($csv);


  foreach ( $csv as $v ) {
    $single = str_getcsv($v, ",");



     $m1[] = array(
      'm-id'     => $counter,
      'name'       => ( isset( $single[1] ) &&  $single[1] != '' && $single[1] != 'NULL' )? $single[1] : '' ,
      'address'    => ( isset( $single[2] ) &&  $single[2] != '' && $single[2] != 'NULL'  )? $single[2] : '' ,
      'city'       => ( isset( $single[3] ) &&  $single[3] != '' && $single[3] != 'NULL'  )? $single[3] : '' ,
      'state'      => ( isset( $single[4] ) &&  $single[4] != '' && $single[4] != 'NULL'  )? $single[4] : '' ,
      'country'    => ( isset( $single[5] ) &&  $single[5] != '' && $single[5] != 'NULL'  )? $single[5] : '' ,
      'zip'        => ( isset( $single[6] ) &&  $single[6] != '' && $single[6] != 'NULL'  )? $single[6] : '' ,
      'tel'        => ( isset( $single[7] ) &&  $single[7] != '' && $single[7] != 'NULL'  )? $single[7] : '' ,
      'mail'       => ( isset( $single[9] ) &&  $single[9] != '' && $single[9] != 'NULL'  )? $single[9] : '' ,
      'web'        => ( isset( $single[10] ) &&  $single[10] != '' && $single[10] != 'NULL'  )? $single[10] : '',
      'lat'        => ( isset( $single[13] ) &&  $single[13] != '' && $single[13] != 'NULL'  )? $single[13] : '' ,
      'lng'        => ( isset( $single[14] ) &&  $single[14] != '' && $single[14] != 'NULL'  )? $single[14] : '',
    );



    $counter++;


  }

  update_option('mediaone_repairers',$m1);


  */

  ?>

<?php if ( file_exists(get_stylesheet_directory() . '/import.csv') ) { ?>

<div class="importmessage">
  <h3>Import file detected </h3>
  <p>Click the button to start the import</p>
  <button class="importcsv-button">IMPORT CSV</button>
</div>


<?php } ?>



<?php  if ( isset( $_GET['type'] ) && $_GET['type'] == 'repairers' ) {  ?>

     <form action="<?php echo admin_url('admin.php?page=mediaone-dash'); ?>" method="post" class="mediaone-map-form" data-type="repairers" >

<?php  } else {  ?>

     <form action="<?php echo admin_url('admin.php?page=mediaone-dash'); ?>" method="post" class="mediaone-map-form" data-type="dealers" >

<?php  }  ?>

<form action="<?php echo admin_url('admin.php?page=mediaone-dash'); ?>" method="post" class="mediaone-map-form" data-type="dealers" >

  <?php wp_nonce_field( 'mediaone-nonce-action', 'mediaone-nonce-field' ); ?>
  <input type="hidden" name="mediaone_main_dash" value="1"  />

  <!-- main roo section -->
  <section id="roo-dashboard-wrapper">


    <!-- roo dashboard header -->
    <header id="roo-dashboard-header">

      <span></span>
      <?php if ( isset( $_GET['type'] ) && $_GET['type'] == 'repairers' ) { ?>

        <h1>Repairer Dashboard</h1>


        <a href="<?php echo admin_url('admin.php?page=mediaone-dash'); ?>">Dealers</a>

      <?php } else {  ?>

        <h1>Dealer Dashboard</h1>


        <a href="<?php echo admin_url('admin.php?page=mediaone-dash&type=repairers'); ?>">Repairers</a>

      <?php }  ?>


    </header>


    <!-- roo dashboard mainbody -->
    <article id="roo-main-body">


      <div class="mediaone-filter-holder">

        <ul class="mediaone-filter">
            <li class="mediaone-filter-char">A</li>
            <li class="mediaone-filter-char">B</li>
            <li class="mediaone-filter-char">C</li>
            <li class="mediaone-filter-char">D</li>
            <li class="mediaone-filter-char">E</li>
            <li class="mediaone-filter-char">F</li>
            <li class="mediaone-filter-char">G</li>
            <li class="mediaone-filter-char">H</li>
            <li class="mediaone-filter-char">I</li>
            <li class="mediaone-filter-char">J</li>
            <li class="mediaone-filter-char">K</li>
            <li class="mediaone-filter-char">L</li>
            <li class="mediaone-filter-char">M</li>
            <li class="mediaone-filter-char">N</li>
            <li class="mediaone-filter-char">O</li>
            <li class="mediaone-filter-char">P</li>
            <li class="mediaone-filter-char">Q</li>
            <li class="mediaone-filter-char">R</li>
            <li class="mediaone-filter-char">S</li>
            <li class="mediaone-filter-char">T</li>
            <li class="mediaone-filter-char">U</li>
            <li class="mediaone-filter-char">V</li>
            <li class="mediaone-filter-char">W</li>
            <li class="mediaone-filter-char">X</li>
            <li class="mediaone-filter-char">Y</li>
            <li class="mediaone-filter-char">Z</li>
            <li class="mediaone-fullwidth-filter">

            </li>
            <li class="mediaone-filter-clear">Clear</li>
        </ul>


      </div>

      <div class="latlongselect">
        <button class="lnglatbutton">Add Map Coordinates</button>
      </div>

      <div class="mediaone-body-holder">
        <ul class="mediaone-title-holder">
          <li>&nbsp;</li>
          <li>Name</li>
          <li>Address</li>
          <li>City</li>
          <li>State</li>
          <li>Country</li>
          <li>Zip</li>
          <li>Tel</li>
          <li>Mail</li>
          <li>Web</li>
          <li>Lat</li>
          <li>Lng</li>
          <li>&nbsp;</li>
        </ul>


        <?php if( is_array( $m1 )  )  { ?>

        <?php foreach ($m1 as $k => $v )  { ?>

        <ul class="mediaone-content-holder">
          <li class="mediaone-deletefield-outer"><button class="mediaone-deletefield">Delete</button></li>
          <input type="hidden" class="id" value="<?php echo stripslashes(  html_entity_decode( $v['m-id'], ENT_QUOTES  ) ); ?>" name="m-id" />
          <li><input type="text" name="name" value="<?php echo stripslashes(  html_entity_decode( $v['name'], ENT_QUOTES  )  ); ?>" class="m-name validate"></li>
          <li><input type="text" name="address" value="<?php echo stripslashes(  html_entity_decode( $v['address'], ENT_QUOTES  )  ); ?>" class="m-address validate"></li>
          <li><input type="text" name="city" value="<?php echo stripslashes(  html_entity_decode( $v['city'], ENT_QUOTES  )  ); ?>" class="m-city validate"></li>
          <li><input type="text" name="state" value="<?php echo stripslashes(  html_entity_decode( $v['state'] , ENT_QUOTES  ) ); ?>" class="m-state validate"></li>
          <li><input type="text" name="country" value="<?php echo stripslashes(  html_entity_decode( $v['country'] , ENT_QUOTES  ) ); ?>" class="m-country validate"></li>
          <li><input type="text" name="zip" value="<?php echo stripslashes(  html_entity_decode( $v['zip'] , ENT_QUOTES  ) ); ?>" class="m-zip"></li>
          <li><input type="text" name="tel" value="<?php echo stripslashes(  html_entity_decode( $v['tel'] , ENT_QUOTES  ) ); ?>" class="m-tel"></li>
          <li><input type="text" name="mail" value="<?php echo stripslashes(  html_entity_decode( $v['mail'] , ENT_QUOTES  ) ); ?>" class="m-mail"></li>
          <li><input type="text" name="web" value="<?php echo stripslashes(  html_entity_decode( $v['web'] , ENT_QUOTES  ) ); ?>" class="m-web"></li>
          <li><input type="text" name="lat" value="<?php echo stripslashes(  html_entity_decode( $v['lat'] , ENT_QUOTES  ) ); ?>" class="m-lat validate"></li>
          <li><input type="text" name="lng" value="<?php echo stripslashes(  html_entity_decode( $v['lng'] , ENT_QUOTES  ) ); ?>" class="m-lng validate"></li>
          <li class="mediaone-savefield-outer"><button class="mediaone-savefield">Save</button></li>
        </ul>
        <?php }  }  ?>

      </div>

      <div class="mediaone-footer-holder">
        <div class="">
          <ul class="mediaone-content-holder">
            <li class="mediaone-deletefield-outer"><button class="mediaone-mapfield">MAP</button></li>
            <input type="hidden" class="id" value="" name="m-id" />
            <li><input type="text" name="name" value="" class="m-name validate"></li>
            <li><input type="text" name="address" value="" class="m-address validate mapfield"></li>
            <li><input type="text" name="city" value="" class="m-city validate mapfield"></li>
            <li><input type="text" name="state" value="" class="m-state validate mapfield"></li>
            <li><input type="text" name="country" value="" class="m-country validate mapfield"></li>
            <li><input type="text" name="zip" value="" class="m-zip mapfield"></li>
            <li><input type="text" name="tel" value="" class="m-tel"></li>
            <li><input type="text" name="mail" value="" class="m-mail"></li>
            <li><input type="text" name="web" value="" class="m-web"></li>
            <li><input type="text" name="lat" value="" class="m-lat validate"></li>
            <li><input type="text" name="lng" value="" class="m-lng validate"></li>
            <li class="mediaone-savefield-outer"><button class="mediaone-addfield">ADD</button></li>
          </ul>
        </div>
      </div>



    </article>
</section>

<div class="roo-update-message">
    <input type="submit" value="Save Changes" name="customer_dash_select" />
</div>

</form>
