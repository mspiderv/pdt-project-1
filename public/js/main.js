// Define some global variables
pickingLocation = false;
defaultMapZoom = 13;
wayLayers = [];
profile = 'walking';

// Semantic UI initialization
$('.ui.accordion').accordion();

// Provide your access token
L.mapbox.accessToken = 'pk.eyJ1IjoibXNwaWRlcnYiLCJhIjoiY2l0d3A5YWMzMDAydzJzbjJybXF0d3I5ciJ9.bXIJdC85ltLCt_zRGQky8w';

// Get button elements
var btn = {
  toggleUserLocation: $('#btn-toggle-user-location'),
  loadUserLocation: $('#btn-load-user-location'),
  pickUserLocation: $('#btn-pick-user-location'),
  centerMap: $('#btn-center-map'),
  deactiveParts: $('#btn-deactive-parts'),
  activeParts: $('#btn-active-parts'),
};

// Create a map in the div #map
var map = L.mapbox.map('map');

// Set map style
L.mapbox.styleLayer('mapbox://styles/mspiderv/ciw12ppxw00c92klkuydr9u3d').addTo(map);

// Helper functions
function setMapCenter(latitude, longitude) {
  map.setView([latitude, longitude], defaultMapZoom);
}

function add(geojson) {
  var layer = L.geoJson(geojson, {
    style: L.mapbox.simplestyle.style
  }).addTo(map);
  return layer;
}

function addFeature(geojson) {
  var layer = L.mapbox.featureLayer(geojson).addTo(map);
  /*layer.on('mouseover', function(e) {
      e.layer.openPopup();
  });
  layer.on('mouseout', function(e) {
      e.layer.closePopup();
  });*/
  return layer;
}

// http://stackoverflow.com/questions/1484506/random-color-generator-in-javascript
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Set view to Bratislava
setMapCenter(48.14349658232668, 17.11167812347412);

// Global variable holding user GeoLocation
userGeoLocation = {
  layer: null,
  geoJson: {
    "type": "Feature",
    "geometry": {
      "type": "Point",
      "coordinates": [null, null] // This will be resolved automatically
    },
    "properties":{
      "marker-color": "#e74c3c",
    }
  },
  loadFromGeoLocation: function() {
    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(function(position) {
        userGeoLocation.set(position.coords.latitude, position.coords.longitude);
        userGeoLocation.centerMap();
      });
    } else {
      alert("ERROR: GeoLocation IS NOT available");
    }
  },
  initialize: function() {
    this.layer = L.mapbox.featureLayer();
  },
  set: function(latitude, longitude) {
    this.geoJson.geometry.coordinates = [longitude, latitude];
    this.layer.setGeoJSON(this.geoJson);
    btn.toggleUserLocation.addClass('active');
    findRouteHandler();
  },
  getAsArray: function() {
    return this.geoJson.geometry.coordinates;
  },
  show: function() {
    this.layer = L.mapbox.featureLayer().addTo(map);
    this.layer.setGeoJSON(this.geoJson);
  },
  hide: function() {
    this.layer.clearLayers();
  },
  centerMap: function() {
    setMapCenter(
      userGeoLocation.geoJson.geometry.coordinates[1],
      userGeoLocation.geoJson.geometry.coordinates[0]);
  }
};
userGeoLocation.initialize();

// Button handlers
btn.toggleUserLocation.on('click', function() {
  if (btn.toggleUserLocation.hasClass('active')) {
    userGeoLocation.hide();
    btn.toggleUserLocation.text(btn.toggleUserLocation.data('show'));
  } else {
    userGeoLocation.show();
    btn.toggleUserLocation.text(btn.toggleUserLocation.data('hide'));
  }
  btn.toggleUserLocation.toggleClass('active');
});

btn.loadUserLocation.on('click', userGeoLocation.loadFromGeoLocation);

btn.pickUserLocation.on('click', function() {
  pickingLocation = true;
  btn.pickUserLocation.addClass('positive');
});

btn.centerMap.on('click', userGeoLocation.centerMap);

// Map clicking functionality
map.on('click', function(ev) {
  if (pickingLocation) {
    pickingLocation = false;
    btn.pickUserLocation.removeClass('positive');
    userGeoLocation.set(ev.latlng.lat, ev.latlng.lng);
  }
});

// Profile changing
var profileButtons = $('#profile-buttons button');
profileButtons.on('click', function() {
  var slectedProfileButton = $(this);
  profileButtons.removeClass('positive');
  slectedProfileButton.addClass('positive');
  profile = slectedProfileButton.data('profile');
  findRouteHandler();
});

// Find the best path
function route(coordinates, color) {
  // Encode coordinates string
  var coordinatesString = [];
  for (coordinate of coordinates) {
    coordinatesString.push(coordinate.join(','));
  }
  coordinatesString = coordinatesString.join(';');

  // Make request URL
  var url = 'https://api.mapbox.com/directions/v5/mapbox/' + profile + '/' + coordinatesString;

  // Send AJAX request
  $.getJSON(
    url,
    {
      access_token: L.mapbox.accessToken,
      overview: 'full'
    },
    function(data, textStatus, jqXHR) {
      wayLayers.push(
        addFeature(
        {
            type: 'Feature',
            geometry: polyline.toGeoJSON(data.routes[0].geometry),
            properties: {
            'stroke': color,
            'stroke-opacity': 0.8,
            'stroke-width': 5,
            }
        })
      );
    }
  );
}

function findRouteHandler() {
  // Clear way layers
  for (var layer of wayLayers) {
    layer.clearLayers();
  }

  // Create array of waypoints
  var waypoints = [];
  
  // We start at the users currect location
  waypoints.push(userGeoLocation.getAsArray());

  // Find routes
  $('#amenities .item.active').each(function(index, element) {
    var startLocation = waypoints[waypoints.length - 1];
    var finishLocation = null;
    var amenity = $(element).data('amenity');
    var longitude = startLocation[0];
    var latitude = startLocation[1];
    $.ajax({
        url: '/api/features/nearest/' + amenity + '/' + longitude+ '/' + latitude,
        async: false,
        dataType: 'json',
        data: {
          selectedParts: getSelectedParts()
        },
        success: function(data, textStatus, jqXHR) {
          var color = getRandomColor();
          // Adjust marker
          data.properties['marker-symbol'] = waypoints.length;
          data.properties['marker-color'] = color;
          
          finishLocation = data.geometry.coordinates;
          waypoints.push(finishLocation);
          route([
            startLocation,
            finishLocation
          ], color);
          wayLayers.push(addFeature(data));
        }
    });
  });
}

var amenities = $('#amenities .item[data-amenity]');
amenities.on('click', function() {
  $(this).toggleClass('active');
  findRouteHandler();
});

$("#amenities").sortable({
  axis: "y",
  stop: findRouteHandler
});

// Showables
amenityLayer = null;

function hideAmenityLayer() {
  if (amenityLayer) {
    amenityLayer.clearLayers();
  }
}

function refreshAmenityLayer() {
  hideAmenityLayer();
  var amenity = $('#showables .item.active').data('amenity');
  if (amenity) {
    showAmenityLayer($('#showables .item.active').data('amenity'));
  }
}

function showAmenityLayer(amenity) {
  $.getJSON('/api/features/amenity/' + amenity, {
    selectedParts: getSelectedParts()
  }, function(data, textStatus, jqXHR) {
    amenityLayer = addFeature(data);
  });
}

$('#showables .item[data-amenity]').on('click', function() {
  hideAmenityLayer();
  var $this = $(this);
  if ($this.hasClass('active')) {
    $this.removeClass('active');
  } else {
    $('#showables .item').removeClass('active');
    $(this).addClass('active');
    showAmenityLayer($(this).data('amenity'));
  }
});

// Set current location to STU FIIT by default
userGeoLocation.set(48.15387250625418, 17.072426676750183);
userGeoLocation.centerMap();
userGeoLocation.show();


/////////////////
var partsLayers = [];

function hidePartsLayer() {
  for (partsLayer of partsLayers) {
    partsLayer.clearLayers();
  }
}

function getSelectedParts() {
  var selectedParts = [];
  $('#parts .item.active').each(function() {
    selectedParts.push($(this).text().trim());
  });
  return selectedParts;
}

function showPartsHandler() {
  var selectedParts = getSelectedParts()
  $.getJSON('/api/features/polygons', {
    selectedParts: selectedParts
  }, function(data, textStatus, jqXHR) {
    hidePartsLayer();
    partsLayers.push(addFeature(data));
  });
}
showPartsHandler();

$('#parts .item').on('click', function() {
  $(this).toggleClass('active');
  showPartsHandler();
  refreshAmenityLayer();
  findRouteHandler();
});

btn.deactiveParts.on('click', function() {
  $('#parts .item').removeClass('active');
  showPartsHandler();
  refreshAmenityLayer();
  findRouteHandler();
});

btn.activeParts.on('click', function() {
  $('#parts .item').addClass('active');
  showPartsHandler();
  refreshAmenityLayer();
  findRouteHandler();
});