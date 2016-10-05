<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;

class GeoController extends Controller
{
    protected function queryToJSON($query, $jsonColumnName = 'json') {
        $rawQuery = DB::raw($query);
        $data = DB::select($rawQuery);
        $rows = collect($data)->pluck($jsonColumnName)->toArray();
        $json = '[' . implode(', ', $rows) . ']';
        return $json;
    }
    
    public function test() {
        return $this->queryToJSON("
            SELECT st_asgeojson(st_transform(way, 4326)) as json
            FROM planet_osm_point
            WHERE amenity = 'restaurant'
        ");
    }
}
