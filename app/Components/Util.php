<?php

namespace App\Components;

use App\Models\City;
use App\Models\DeliveryPrice;
use App\Models\DeliveryPriceByCity;
use App\Models\DeliveryPriceByZone;
use App\Models\DeliveryTimeByCity;
use App\Models\DeliveryTimeByZone;
use App\Models\Product;
use App\Models\ScheduleDay;
use App\Models\ShippingAddress;
use App\Models\Store;
use App\Models\Tax;
use App\Models\Zone;
use Carbon\Carbon;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class Util
{
    /**
     * @param $url
     * @return null
     */
    public static function getIdVideoYT($url)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return isset($match[1]) ? $match[1] : null;
        }
        return null;
    }

    /**
     * @param $minutes
     * @return bool|string
     */
    public static function minutesToHumanTime($minutes)
    {
        $result = "0m";
        if ($minutes) {
            $minutes = $minutes * 60;// transform to seconds
            $dtF = new \DateTime('@0');
            $dtT = new \DateTime("@$minutes");
            $result = $dtF->diff($dtT)->format('%ad %hh %im');
            if (strpos($result, 'd') != false) {
                $days = (int)substr($result, 0, strpos($result, 'd'));
                if ($days == 0)
                    $result = substr($result, strpos($result, 'd') + 1);
            }
            if (strpos($result, 'h') != false) {
                $hours = (int)substr($result, 0, strpos($result, 'h'));
                if ($hours == 0)
                    $result = substr($result, strpos($result, 'h') + 1);
            }
            $result = trim($result);
        }
        return $result;
    }

    /**
     * @param $time
     * @return float|int
     * @example $time= '1d 3h 45m'
     */
    public static function transformTimeToMinutes($time)
    {
        $days = 0;
        $hours = 0;
        $minutes = 0;
        $time = str_replace(' ', '', $time);
        $time = str_replace(' ', '', $time);
        if (strpos($time, 'd') != false) {
            $days = (int)substr($time, 0, strpos($time, 'd'));
            $time = substr($time, strpos($time, 'd') + 1);
        }
        if (strpos($time, 'h') != false) {
            $hours = (int)substr($time, 0, strpos($time, 'h'));
            $time = substr($time, strpos($time, 'h') + 1);
        }
        if (strpos($time, 'm') != false) {
            $minutes = (int)substr($time, 0, strpos($time, 'm'));
        }
        $result = $minutes + $hours * 60 + $days * 24 * 60;
        return $result;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return null
     */
    public static function findZoneFromCoordinates($latitude, $longitude)
    {
        $point = new Point($latitude, $longitude);
        $zone = Zone::where('status', 'ACTIVE')
            ->contains('polygon_spatial', $point)
            ->first();
        return $zone ?? null;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return null
     */
    public static function findCityFromCoordinates($latitude, $longitude)
    {
        $point = new Point($latitude, $longitude);
        $city = City::where('status', 'ACTIVE')
            ->contains('polygon_spatial', $point)
            ->first();
        return $city ?? null;
    }

    /**
     * @param ShippingAddress $shippingAddress
     * @return null
     */
    public static function findCityFromShippingAddress(ShippingAddress $shippingAddress)
    {
        $point = $shippingAddress->location;
        $city = City::where('status', 'ACTIVE')
            ->contains('polygon_spatial', $point)
            ->first();
        return $city ?? null;
    }

    /**
     * @param Zone $zone_1
     * @param Zone $zone_2
     * @param string $type
     * @return mixed
     */
    public static function getTimeDeliveryByZones(Zone $zone_1, Zone $zone_2, $type = "DELIVERY")
    {
        $time = DeliveryTimeByZone::where('zone_id_1', $zone_1->id)
            ->where('zone_id_2', $zone_2->id)
            ->where('type', $type)
            ->first();
        return $time ?? null;
    }

    /**
     * @param City $city_1
     * @param City $city_2
     * @param string $type
     * @return mixed
     */
    public static function getTimeDeliveryByCities(City $city_1, City $city_2, $type = "DELIVERY")
    {
        $time = DeliveryTimeByCity::where('city_id_1', $city_1->id)
            ->where('city_id_2', $city_2->id)
            ->where('type', $type)
            ->first();
        return $time ?? null;
    }

    /**
     * @param $minutes
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public static function getDeliveryPriceCategory($minutes)
    {
        $hours = $minutes * 60;
        $query = DeliveryPrice::query();
        $price_category = $query->where('begin_time', '<=', $hours)
            ->where('end_time', '>=', $hours)
            ->where('status', '=', 'ACTIVE')
            ->first();
        return $price_category ?? null;
    }

    public static function getPriceDeliveryByZones(Zone $zone_1, Zone $zone_2, DeliveryPrice $delivery_price)
    {
        $price = DeliveryPriceByZone::where('zone_id_1', $zone_1->id)
            ->where('zone_id_2', $zone_2->id)
            ->where('delivery_price_id', '=', $delivery_price->id)
            ->first();
        return $price ?? null;
    }

    public static function getPriceDeliveryByCities(City $city_1, City $city_2, DeliveryPrice $delivery_price)
    {
        $price = DeliveryPriceByCity::where('city_id_1', $city_1->id)
            ->where('city_id_2', $city_2->id)
            ->where('delivery_price_id', '=', $delivery_price->id)
            ->first();
        return $price ?? null;
    }

    /**
     * @param City $city
     * @return float|int
     */
    public static function getTax(City $city)
    {
        $taxes = $city->taxes()->where('taxes.status', '=', 'ACTIVE')->get();
        $total = 0.00;
        foreach ($taxes as $tax) {
            $total += (float)$tax->value;
        }
        return $total > 0 ? (float)$total / 100 : 0.00; //ex: 0.12
    }

    public static function getTaxFromCoordinates($latitude, $longitude)
    {
        $total = 0.00;
        $city = self::findCityFromCoordinates($latitude, $longitude);
        if ($city) {
            $taxes = $city->taxes()
                ->where('status', '=', 'ACTIVE')
                ->get();
            foreach ($taxes as $tax) {
                $total += (float)$tax->value;
            }
        }
        return $total;
    }

    public static function isOpen($store_id = null)
    {
        $now = Carbon::now();
        $current_hour = $now->format('H:i:s');
        $day = $now->dayOfWeekIso;
        $day_of_week = ScheduleDay::DAYS_ISO[$day];
        $date = $now->format('Y-m-d');

        /**
         * Comparison Holiday
         */
        $query_holiday = ScheduleDay::query();
        if ($store_id) {
            $store = Store::where('id', '=', $store_id)->where('status', '=', 'ACTIVE')->first();
            if (!$store)
                return false;
            $query_holiday->where('store_id', '=', $store_id);
            $query_holiday->where('is_global', '=', 'NO');
        } else {
            $query_holiday->where('is_global', '=', 'YES');
        }
        $query_holiday->where('day', '=', ScheduleDay::HOLIDAY);
        $query_holiday->where('start_date', '<=', $date);
        $query_holiday->where('end_date', '>=', $date);
        $holiday = $query_holiday->first();
        if ($holiday) {
            $exists_schedule = $holiday->times()
                ->where('start', '<=', $current_hour)
                ->where('end', '>=', $current_hour)
                ->first();
            if ($exists_schedule)
                return true;
        }
        /**
         * End comparison holiday
         */

        /**
         * Comparision normal day
         */
        $query_schedule = ScheduleDay::query();
        if ($store_id) {
            $query_schedule->where('store_id', '=', $store_id);
            $query_schedule->where('is_global', '=', 'NO');
        } else {
            $query_schedule->where('is_global', '=', 'YES');
        }
        $query_schedule->where('day', '=', $day_of_week);
        $schedule_day = $query_schedule->first();

        if ($schedule_day) {
            $exists_schedule = $schedule_day->times()
                ->where('start', '<=', $current_hour)
                ->where('end', '>=', $current_hour)
                ->first();
            if ($exists_schedule)
                return true;
        }
        /**
         * End Comparison normal day
         */
        return false;
    }

    /**
     * @param Product $product
     * @param int $amount
     * @param $vat
     * @param string $type
     * @return array|null
     */
    public static function calculatePriceProduct(Product $product, $amount = 1, $vat, $type = 'BUY')
    {
        $unit_price = self::getPriceByProduct($product, $type);
        if ($unit_price == null) return null;

        $subtotal = $unit_price * $amount;
        $tax_calculated = $subtotal * $vat;
        $total = $subtotal + $tax_calculated;
        return [
            'unit_price' => $unit_price,
            'amount' => $amount,
            'vat' => $vat, // IVA
            'vat_calculated' => $tax_calculated, // IVA calculado
            'vat_tax_base' => $subtotal,// base imponible del IVA
            'total_price' => $total // total del producto incluyendo IVA
        ];
    }

    /**
     * @param Product $product
     * @param string $type
     * @return mixed|null
     */
    public static function getPriceByProduct(Product $product, $type = 'BUY')
    {
        $price_category = $product->prices_categories()
            ->where('type', '=', $type)
            ->where('status', '=', 'ACTIVE')
            ->first();
        if (!$price_category) return 0;
        $price = $product->prices()
            ->where('price_category_id', '=', $price_category->id)
            ->first();
        if (!$price) return 0;
        return $price->price;
    }

    public static function getStoreNearProduct(Product $product, Point $point)
    {
        $stores_id = $product->stores()->get()->pluck('id')->toArray();
        $query = Store::query();
        $query->where('status', '=', 'ACTIVE');
        $query->whereIn('id', $stores_id);
        $query->distanceValue('location', $point);
        $query->orderBy('distance', 'ASC');
        $store = $query->firstOrFail();
        return $store;
    }

    /**
     * @return array
     */
    public static function getImageDefault()
    {
        $urlImageDefault = config('app.url') . "/api/img/images/productDefault.png";

        $result = [
            'url' => $urlImageDefault,
            'thumbnailUrl' => $urlImageDefault,
            'fileName' => 'productDefault.png'
        ];

        return $result;
    }


    public static function getTaxes($cityId = null)
    {
        $result = [];
        if ($cityId) {

            $taxes = Tax::query()
                ->select('taxes.*')
                ->join('taxes_by_city', 'taxes_by_city.taxes_id', '=', 'taxes.id')
                ->where('taxes_by_city.city_id', $cityId)
                ->groupBy('taxes.id')
                ->get();

            foreach ($taxes as $tax) {
                $result[] = [
                    'value' => round($tax->value, 4),
                    'name' => $tax->name
                ];
            }

        } else {
            $result = [self::getDefaultTax()];
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getDefaultTax()
    {
        $tax = Tax::query()
            ->orderBy('default', 'desc')
            ->first();

        $result = [
            'value' => round($tax->value, 4),
            'name' => $tax->name
        ];

        return $result;
    }

    /**
     * @param $unitPrice
     * @param $amount
     * @param $vat
     * @param int $percentageDiscount
     * @param int $additionalCost
     * @return array
     */
    public static function calculatePrice($unitPrice, $amount, $vat, $percentageDiscount = 0, $additionalCost = 0)
    {

        $baseDiscount = round(($unitPrice * $percentageDiscount) / 100, 4);
        $totalDiscount = round($baseDiscount * $amount, 4);
        $taxBase = round($unitPrice * $amount + $additionalCost, 4);
        $totalVat = round($taxBase * $vat / 100, 4);
        $total = round($taxBase + $totalVat - $totalDiscount, 4);

        return [
            'baseDiscount' => $baseDiscount,
            'totalDiscount' => $totalDiscount,
            'taxBase' => $taxBase,
            'totalVat' => $totalVat,
            'total' => $total
        ];
    }


}