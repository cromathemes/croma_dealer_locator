jQuery(function( $ ) {

var roo = roo || {};

m1 = {

  e : {
    save: '<button class="mediaone-savefield">Save</button>',
    del : '<button class="mediaone-deletefield">Delete</button>',
    mask: '<div class="mediaone-save-mask"></div>'
  },

  t : {
    ctr : 0,
    el  : 0,
    int : null,
    arr : [],
    time: 250,
    beating: false,
    saving: false
  },

  init: function() {

    m1.e.outers = $('.mediaone-body-holder');


    $('<div class="mediaone-save-mask"><img class="mediaone-spinner" src="' + m1.getBgUrl($('#roo-dashboard-header').find('span') ) + '" /></div>').appendTo('body');

    m1.e.mask = $('.mediaone-save-mask');

    $(document).on('click','.mediaone-addfield', function(e){
      e.preventDefault();

      m1.clickAdd( $(this) );

    });

    $(document).on('click','.importcsv-button', function(e){
      e.preventDefault();

      m1.importCSV();

    });

    $(document).on('click','.lnglatbutton', function(e){
      e.preventDefault();

      m1.updateLNLT();

    });

    $(document).on('click','.mediaone-deletefield', function(e){
      e.preventDefault();

      m1.deleteField( $(this) );

    });

    $(document).on('click','.mediaone-filter-char', function(e){
      e.preventDefault();

      m1.filterField( $(this) );

    });

    $(document).on('click','.mediaone-filter-clear', function(e){
      e.preventDefault();

      m1.filterClear();

    });


    $(document).on('click','.mediaone-savefield', function(e){
      e.preventDefault();

      m1.saveField( $(this) );

    });


    $(document).on('click','.mediaone-mapfield', function(e){
      e.preventDefault();

      m1.mapField( $(this) );

    });


    m1.e.type = $('.mediaone-map-form').data('type');

  },

  updateLNLT: function(){

      m1.t.arr = null;
      m1.t.arr = $('.mediaone-body-holder').find('ul.mediaone-content-holder');
      m1.t.ctr = 0;
      m1.t.el  = m1.t.arr.length;

      m1.t.beating = false;

      m1.t.int = setInterval(m1.processLNLT, m1.t.time );

      $('.mediaone-save-mask').fadeIn('slow');

  },

  processLNLT: function() {
    if ( m1.t.beating ) {
      return;
    }

    if ( m1.t.el <= 0 ) {
      clearInterval(m1.t.int);
      return;
      m1.t.beating = false;
    }


    m1.t.beating = true;


    var   outer    = $(m1.t.arr[m1.t.ctr]),
          id       = outer.find('.id').val(),
          lt       = outer.find('.m-lat'),
          lg       = outer.find('.m-lng'),
          addr     = '',
          saveEl   = outer.find('.mediaone-savefield');

    addr = outer.find('.m-address').val();
    addr += ' ';
    addr += outer.find('.m-city').val();
    addr += ' ';
    addr += outer.find('.m-state').val();
    addr += ' ';
    addr += outer.find('.m-country').val();

    if ( lt.val() == '' || lg.val() == '' ){

      $.ajax({
        url    : ajaxurl,
        data   : 'action=mediaone_getlat&types=' +  m1.e.type   +  '&type=getlat&id='  + id + '&addr=' + encodeURIComponent(addr),
        contentType: 'application/x-www-form-urlencoded',
        type   : 'GET',
        success: function( response ) {

            var ltlg = '';
            if (response !== 0){
              ltlg = response.split('||');
              lg.val(ltlg[1]);
              lt.val(ltlg[0]);
              m1.t.beating = false;
            }

        }

      });

    } else {
      m1.t.beating = false;
    }

    m1.t.ctr = m1.t.ctr + 1;
    if (m1.t.ctr === m1.t.el ){
      clearInterval(m1.t.int);
      m1.t.beating = false;
      $('.mediaone-save-mask').fadeOut('slow');
      // location.reload();
      return;
    }

  },

  clickAdd: function(el){
    var outer     = el.parents('ul'),
        inp       = outer.find('input'),
        validated = outer.find('input.validate'),
        serstring;

    if ( m1.valMess(validated) ){

      m1.e.clone = outer.clone();

      m1.doButtons( m1.e.clone );

      id = m1.prepareID();

      m1.e.clone.find('.id').val(id);

      $(m1.e.clone).appendTo( m1.e.outers );

      m1.cleanAddField();

      serstring = m1.e.clone.find('input').serialize();

      m1.updateAddField( serstring, false );

    }

  },


  mapField: function(el){
      var outer = el.parents('ul'),
          inp   = outer.find('.mapfield'),
          lt    = outer.find('.m-lat'),
          lg    = outer.find('.m-lng'),
          addr = '';

      inp.each( function() {

        addr += $(this).val() + ' ';
      });

      if ( $.trim(addr) == '' ) return;

      $('.mediaone-save-mask').fadeIn('slow');

      $.ajax({
        url    : ajaxurl,
        data   : 'action=mediaone_getlat&type=getlat&types=' +  m1.e.type   +  '&addr=' + encodeURIComponent($.trim(addr)),
        contentType: 'application/x-www-form-urlencoded',
        type   : 'GET',
        success: function( response ) {

            var ltlg = '';
            $('.mediaone-save-mask').fadeOut('slow');
            if (response !== 0){
              ltlg = response.split('||');
              lg.val(ltlg[1]);
              lt.val(ltlg[0]);
            }

        }

      });



  },

  importCSV: function() {

    $('.mediaone-save-mask').fadeIn('slow');

    $.ajax({
      url    : ajaxurl,
      data   : 'action=mediaone_importcsv&type=importcsv',
      contentType: 'application/x-www-form-urlencoded',
      type   : 'GET',
      success: function( response ) {

        location.reload();
         
      }

    });


  },


  getBgUrl: function(el) {

    var bg_url = $(el).css('background-image');

    bg_url = /^url\((['"]?)(.*)\1\)$/.exec(bg_url);
    bg_url = bg_url ? bg_url[2] : "";

    return bg_url;

  },

  updateAddField: function(ser,fade ){

    if ( !fade ){
      m1.e.mask.fadeIn('slow');
    }
   

    $.ajax({
      url    : ajaxurl,
      data   : encodeURIComponent('action=mediaone_addtomap&type=addtomap&types=' + m1.e.type + '&' + ser),
      type   : 'POST',
      success: function( response ) {

          console.log(response);

          if ( !fade ){
            m1.e.mask.fadeOut('slow');
          }

          
      }

    });

  },

  valMess: function(el) {
    var val = 0;


    $(el).removeClass('valerror');

    $(el).each(function() {
      var ø = $(this),
          ti = ø.val();

      if (ti == ''){
        val++;
        ø.addClass('valerror');
      }

    });

    return ( val >= 1 )? false  : true ;

  },

  doButtons: function(el){
    var delfield  = $(el).find('.mediaone-deletefield-outer'),
        savefield = $(el).find('.mediaone-savefield-outer');

    $(m1.e.clone).find(delfield).html(m1.e.del);

    $(m1.e.clone).find(savefield).html(m1.e.save);

  },

  prepareID: function() {
    var id       = 0,
        idFields = m1.e.outers.find('.id');

    idFields.each(function() {
      var ø   = $(this),
          val = ø.val();

      id = ( val != '' &&  parseInt( val )  <= id )? id : val;

    });

    id++;

    return id;

  },

  cleanAddField: function() {

    $('.mediaone-footer-holder').find('input').removeClass('valerror').val('');

  },

  deleteField: function(el) {
    var outer = $(el).parents('.mediaone-content-holder'),
        id    = outer.find('.id').val();

    m1.e.mask.fadeIn('slow');

    $.ajax({
      url    : ajaxurl,
      data   : encodeURIComponent('action=mediaone_deletemap&type=deletemap&types=' + m1.e.type + '&id=' + id),
      type   : 'POST',
      success: function( response ) {


        m1.e.mask.fadeOut('slow');

        outer.remove();
      }

    });


  },

  saveField: function(el,fade) {
    var outer     = el.parents('ul'),
        inp       = outer.find('input'),
        validated = outer.find('input.validate'),
        serstring;

    if ( m1.valMess(validated) ){

      serstring = inp.serialize();

      console.log()

      m1.updateAddField( serstring,fade );

    }


  },

  filterField: function(el) {
    var car     = el.html(),
        filters = $('.mediaone-body-holder').find('.mediaone-content-holder');

    filters.each(function() {
      var ø     = $(this),
          name  = ø.find('.m-name').val(),
          first = name.charAt(0);

      if (car === first.toUpperCase() || car === first.toLowerCase() ){

        ø.show();
      } else {

        ø.hide();
      }


    });


    $('.mediaone-filter-char').removeClass('filter-active');

    el.addClass('filter-active');


  },

  filterClear: function() {
    $('.mediaone-body-holder').find('.mediaone-content-holder').show();
    $('.mediaone-filter').find('.filter-active').removeClass('filter-active');
  }




}


m1.init();


});
