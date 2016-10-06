// Provide your access token
L.mapbox.accessToken = 'pk.eyJ1IjoibXNwaWRlcnYiLCJhIjoiY2l0d3A5YWMzMDAydzJzbjJybXF0d3I5ciJ9.bXIJdC85ltLCt_zRGQky8w';

// Create a map in the div #map
var map = L.mapbox.map('map', 'mapbox.streets');

map.setView([48.14349658232668, 17.11167812347412], 14);

function add(geojson) {
  var layer = L.geoJson(geojson, {
    style: L.mapbox.simplestyle.style
  }).addTo(map);
  return layer;
}

function addFeature(geojson) {
  var layer = L.mapbox.featureLayer(geojson).addTo(map);
  layer.on('mouseover', function(e) {
      e.layer.openPopup();
  });
  layer.on('mouseout', function(e) {
      e.layer.closePopup();
  });
  return layer;
}

$(function() {
  // Restaurants
  $.getJSON('/api/features/restaurants', {}, function(data, textStatus, jqXHR) {
    addFeature(data);
  });

  // Cafes
  $.getJSON('/api/features/cafes', {}, function(data, textStatus, jqXHR) {
    addFeature(data);
  });

  // Banks
  $.getJSON('/api/features/banks', {}, function(data, textStatus, jqXHR) {
    addFeature(data);
  });
});