<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;

class FeaturesController extends Controller
{
    // key is amenity; value is title
    protected $titleMapping = [
        "toilets" => "Toaleta",
        "bank" => "Banka",
        "pharmacy" => "Lekáreň",
        "restaurant" => "Reštaurácia",
        "post_box" => "Pošta",
        "pub" => "Krčma",
        "cafe" => "Kaviareň",
    ];

    // key is amenity; value is marker color
    protected $markerColorMapping = [
        "toilets" => "#95a5a6",
        "bank" => "#2980b9",
        "pharmacy" => "#1abc9c",
        "restaurant" => "#e74c3c",
        "post_box" => "#8e44ad",
        "pub" => "#f39c12",
        "cafe" => "#a67b5b",
    ];

    // key is amenity; value is marker symbol
    protected $markerSymbolMapping = [
        "toilets" => "toilets",
        "bank" => "bank",
        "pharmacy" => "pharmacy",
        "restaurant" => "restaurant",
        "post_box" => "commercial",
        "pub" => "beer",
        "cafe" => "cafe",
    ];

    // Resolve point title by name and amenity
    protected function resolveTitle($name, $amenity = null) {
        if ($name != "") {
            return $name;
        } else {
            if (isset($this->titleMapping[$amenity])) {
                return $this->titleMapping[$amenity];
            } else {
                return "Bez názvu";
            }
        }
    }

    // Resolve point marker color by amenity
    protected function resolveMarkerColor($amenity) {
        if (isset($this->markerColorMapping[$amenity])) {
            return $this->markerColorMapping[$amenity];
        } else {
            return "#34495e";
        }
    }

    // Resolve point marker symbol by amenity
    protected function resolveMarkerSymbol($amenity) {
        if (isset($this->markerSymbolMapping[$amenity])) {
            return $this->markerSymbolMapping[$amenity];
        } else {
            return "";
        }
    }

    // Performs the raw query over the database
    protected function query($query) {
        $rawQuery = DB::raw($query);
        $data = DB::select($rawQuery);
        return $data;
    }

    // Get the nearest point by amenity and location
    public function getNearest($amenity, $longitude, $latitude) {
        // Prevent SQL injection
        $longitude = floatval($longitude);
        $latitude = floatval($latitude);
        $amenity = $this->escapeAmenity($amenity);

        $whereNamesClausule = $this->whereNamesClausule();

        // Find the nearest point from my current location
        $point = $this->query("
            SELECT
                planet_osm_point.name,
                planet_osm_point.amenity,
                st_asgeojson(st_transform(planet_osm_point.way, 4326)) as geojson,
                st_distance(
                    ST_GeomFromText('POINT($longitude $latitude)', 4326),
                    planet_osm_point.way
                ) as distance
            FROM
                planet_osm_point
            JOIN planet_osm_polygon
                ON ST_Contains(planet_osm_polygon.way, planet_osm_point.way)
            WHERE
                planet_osm_point.amenity = '$amenity'
                AND
                planet_osm_polygon.$whereNamesClausule
            ORDER BY
                distance ASC
            LIMIT 1
        ");

        if (count($point) > 0) {
            $point = $point[0];
        } else {
            return null;
        }

        return [
            'type' => 'Feature',
            'geometry' => json_decode($point->geojson),
            'properties' => [
                'title' => $this->resolveTitle($point->name, $amenity)
            ]
        ];
    }

    // Get all points by amenity
    public function getAmenity($amenity) {
        $data = [];

        $whereNamesClausule = $this->whereNamesClausule();

        $points = $this->query("
            SELECT
                planet_osm_point.name,
                planet_osm_point.amenity,
                st_asgeojson(st_transform(planet_osm_point.way, 4326)) as geojson
            FROM
                planet_osm_point
            JOIN planet_osm_polygon
                ON ST_Contains(planet_osm_polygon.way, planet_osm_point.way)
            WHERE
                planet_osm_point.amenity = '$amenity'
                AND
                planet_osm_polygon.$whereNamesClausule
        ");

        foreach ($points as $point) {
            $pointGeoJSON = json_decode($point->geojson);

            // Add point
            $point = [
                'type' => 'Feature',
                'geometry' => $pointGeoJSON,
                'properties' => [
                    'title' => $this->resolveTitle($point->name, $amenity),
                    'marker-color' => $this->resolveMarkerColor($amenity),
                    'marker-symbol' => $this->resolveMarkerSymbol($amenity)
                ]
            ];

            // Save point
            $data[] = $point;
        }

        return $data;
    }

    // Escape amenity string (SQL injection protection)
    protected function escapeAmenity($amenity) {
        return preg_replace("/[^a-zA-Z0-9_]+/", "", $amenity);
    }

    protected function whereNamesClausule() {
        $namesArray = \Request::input('selectedParts');
        if (count($namesArray) > 0) {
            return "name IN ('" . implode("', '", $namesArray) . "')";
        } else {
            return "name IN ('')";
        }
    }

    // Get polygon by name
    public function getPolygons() {
        $data = [];

        $whereNamesClausule = $this->whereNamesClausule();

        $polygons = $this->query("
            SELECT
                name,
                st_asgeojson(st_transform(way, 4326)) as geojson
            FROM
                planet_osm_polygon
            WHERE
                $whereNamesClausule
        ");

        foreach ($polygons as $polygon) {
            $data[] = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geojson),
                'properties' => [
                    'title' => $this->resolveTitle($polygon->name),
                    'fill' => '#000',
                    'fill-opacity' => 0.05,
                    'stroke' => '#fff',
                    'stroke-opacity' => 0.5,
                    'stroke-width' => 2
                ]
            ];
        }

        return $data;
    }
    
    /*public function getRoads() {
        $data = [];

        $roads = $this->query("
            SELECT st_asgeojson(st_transform(way, 4326)) as geojson
            FROM planet_osm_network
            JOIN
            (SELECT * FROM pgr_bdDijkstra('
                SELECT way AS id, 
                    start_id::int4 AS source, 
                    end_id::int4 AS target, 
                    st_distance(st_startpoint, st_endpoint)::float8 AS cost
                    FROM planet_osm_network',
                1,
                5,
                false,
                false)) AS route
            ON
            planet_osm_network.start_id = route.id1;
        ");

        foreach ($roads as $road) {
            $geojson = json_decode($road->geojson);
            $item = [
                'type' => 'Feature',
                'geometry' => $geojson,
                'properties' => [
                    "stroke" => "#000",
                    "stroke-opacity" => 1,
                    "stroke-width" => 4
                ]
            ];

            $data[] = $item;
        }

        return $data;
    }*/
}
