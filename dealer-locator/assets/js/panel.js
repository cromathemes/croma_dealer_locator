jQuery(document).ready(function($) {

/**
 * @extends
 * @constructor
 */
function ScottyLocator() {
  $.extend(this, new storeLocator.StaticDataFeed);

  var that = this;

  that.setStores(that.parse_());

}


function trimhttp(url) {
    return url.replace(/^https?\:\/\//i, "");
}


/**
 * @private
 * @param {string} csv
 * @return {!Array.<!storeLocator.Store>}
 */
ScottyLocator.prototype.parse_ = function() {
  var stores   = [],
      mapobj   = JSON.parse(mapData.dealer),
      counter  = 1,
      keyMap;


  for (var key in mapobj) {

    var position = new google.maps.LatLng(  parseFloat(mapobj[key].lat) , parseFloat(mapobj[key].lng) ),
        locality = this.join_([ mapobj[key].city, mapobj[key].state ], ', '),
        country  = this.join_([ mapobj[key].country, mapobj[key].zip ], ', '),
        tel      = (mapobj[key].tel !== '' )? "<b>TEL:</b> " + mapobj[key].tel : '',
        web      = (mapobj[key].web !== 'NULL' )? "<b>WWW:</b> <a href='http://" + trimhttp(mapobj[key].web) + "' target='_blank'>" + mapobj[key].web + "</a>": '';

    var store = new storeLocator.Store(counter, position, '', {
      title  : mapobj[key].name,
      address: this.join_([mapobj[key].address, locality, country, tel, web], '<br>')
    });
    stores.push(store);
    counter++;

  }

  return stores;
};

/**
 * Joins elements of an array that are non-empty and non-null.
 * @private
 * @param {!Array} arr array of elements to join.
 * @param {string} sep the separator.
 * @return {string}
 */
ScottyLocator.prototype.join_ = function(arr, sep) {
  var parts = [];
  for (var i = 0, ii = arr.length; i < ii; i++) {
    arr[i] && parts.push(arr[i]);
  }
  return parts.join(sep);
};


/**
 * Very rudimentary CSV parsing - we know how this particular CSV is formatted.
 * IMPORTANT: Don't use this for general CSV parsing!
 * @private
 * @param {string} row
 * @return {Array.<string>}
 */
ScottyLocator.prototype.parseRow_ = function(row) {
  // Strip leading quote.
  if (row.charAt(0) == '"') {
    row = row.substring(1);
  }
  // Strip trailing quote. There seems to be a character between the last quote
  // and the line ending, hence 2 instead of 1.
  if (row.charAt(row.length - 2) == '"') {
    row = row.substring(0, row.length - 2);
  }

  row = row.split(';');

  return row;
};


/**
 * Creates an object mapping headings to row elements.
 * @private
 * @param {Array.<string>} headings
 * @param {Array.<string>} row
 * @return {Object}
 */
ScottyLocator.prototype.toObject_ = function(headings, row) {
  var result = {};
  for (var i = 0, ii = row.length; i < ii; i++) {
    result[headings[i]] = row[i];
  }
  return result;
};




/**
 * Adds The map object
 * @private
 * @param {Array.<string>} headings
 * @param {Array.<string>} row
 * @return {Object}
 */

google.maps.event.addDomListener(window, 'load', function() {
  var map = new google.maps.Map(document.getElementById('m1-map-canvas'), {
    center    : new google.maps.LatLng(48.653825 , -123.413175),
    zoom      : 13,
    mapTypeId : google.maps.MapTypeId.ROADMAP
  });

  var panelDiv = document.getElementById('m1-panel'),
      data     = new ScottyLocator;

  var view = new storeLocator.View(map, data, {
    geolocation: false
  });

  new storeLocator.Panel(panelDiv, {
    view: view
  });


});


});
