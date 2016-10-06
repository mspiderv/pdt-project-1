<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;

class FeaturesController extends Controller
{
    /*protected function queryToJSON($query, $jsonColumnName = 'geojson', $decode = true) {
        $rawQuery = DB::raw($query);
        $data = DB::select($rawQuery);
        $rows = collect($data)->pluck($jsonColumnName)->toArray();
        $geojson = '[' . implode(', ', $rows) . ']';
        if ($decode) $data = json_decode($geojson);
        return $data;
    }*/

    protected function query($query) {
        $rawQuery = DB::raw($query);
        $data = DB::select($rawQuery);
        return $data;
    }

    protected function feature($amenity, array $properties = []) {
        $data = [];

        $points = $this->query("
            SELECT 
                name,
                amenity,
                st_asgeojson(st_transform(way, 4326)) as geojson
            FROM planet_osm_point
            WHERE amenity = '$amenity'
            LIMIT 500
        ");

        foreach ($points as $point) {
            // Get point geojson data
            $pointGeoJSON = json_decode($point->geojson);

            // Add point
            $point = [
                // this feature is in the GeoJSON format => see geojson.org for the full specification
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    // coordinates here are in longitude, latitude order because
                    // x, y is the standard for GeoJSON and many formats
                    'coordinates' => $pointGeoJSON->coordinates
                ],
                'properties' => [
                    'title' => ($point->name == "" ? "Bez názvu" : $point->name),
                    // one can customize markers by adding simplestyle properties
                    // https =>//www.mapbox.com/guides/an-open-platform/#simplestyle
                ]
            ];

            // Add custom properties
            $point['properties'] += $properties;

            // Save point
            $data[] = $point;
        }

        return $data;
    }
    
    public function getRestaurants() {
        return $this->feature('restaurant', [
            'marker-color' => '#e74c3c',
            'marker-symbol' => 'restaurant',
        ]);
    }

    public function getCafes() {
        return $this->feature('cafe', [
            'marker-color' => '#a67b5b',
            'marker-symbol' => 'cafe',
        ]);
    }

    public function getBanks() {
        return $this->feature('bank', [
            'marker-color' => '#2980b9',
            'marker-symbol' => 'bank',
        ]);
    }
}
