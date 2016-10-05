// Provide your access token
L.mapbox.accessToken = 'pk.eyJ1IjoibXNwaWRlcnYiLCJhIjoiY2l0d3A5YWMzMDAydzJzbjJybXF0d3I5ciJ9.bXIJdC85ltLCt_zRGQky8w';

// Create a map in the div #map
var map = L.mapbox.map('map', 'mapbox.streets');

map.setView([48.14349658232668, 17.11167812347412], 14);

function add(geojson) {
    return L.geoJson(geojson, {
        style: L.mapbox.simplestyle.style
    }).addTo(map);
}

/*add({
    "type": "Feature",
    "geometry": {
      "type": "LineString",
      "coordinates": [[0.29,0.84],[0.28,0.84],[0.29,0.84],[0.28,0.84],[0.29,0.84],[0.28,0.84],[0.29,0.84],[0.28,0.84],[0.28,0.83],[0.29,0.83],[0.28,0.83],[0.28,0.84],[0.28,0.83],[0.28,0.84]]
    },
    "properties": {
      "stroke": "yellow",
      "stroke-width": 5
    }
});*/

/*add({
  "type": "Feature",
  "geometry": {
    "type": "Point",
    "coordinates": [0.29, 0.84]
  },
  "properties": {
    "name": "Dinagat Islands"
  }
});*/

$(function() {
  $.getJSON('http://127.0.0.1/api/test', {}, function(data, textStatus, jqXHR) {
    add(data);
  });
});