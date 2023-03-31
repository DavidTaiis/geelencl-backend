<?php

namespace App\Traits;


use App\Models\Zone;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\DB;


trait UtilGeographic
{
    /**
     * @param $latitude
     * @param $longitude
     * @return bool
     */
    public function hasCoverage($latitude, $longitude)
    {
        return $this->getZoneByCoordinates($latitude, $longitude) != null;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return mixed
     */
    public static function getZoneByCoordinates($latitude, $longitude)
    {
        $point = new Point($latitude, $longitude);
        $zone = Zone::query()
            ->where('status', 'ACTIVE')
            ->contains('polygon_spatial', $point)
            ->first();
        return $zone;
    }

    /**
     * @param null $zones
     * @return array
     */
    public static function getStoresWithinZones($zones = null)
    {
        $stores = DB::select(
            "SELECT store.id as store_id, store.name as store_name
                FROM zone,
                     store
                WHERE ST_CONTAINS(zone.polygon_spatial, store.location)
                AND zone.id in ({$zones})
                GROUP BY  store.id
            "
        );
        $result = collect($stores)->toArray();
        return $result;
    }

}