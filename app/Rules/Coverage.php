<?php

namespace App\Rules;

use App\Models\City;
use App\Models\Zone;
use GeoJson\Geometry\Point;
use Illuminate\Contracts\Validation\Rule;

class Coverage implements Rule
{
    /**
     * @var string
     */
    private $entity;
    /**
     * @var string
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $latitude = $value['latitude'];
        $longitude = $value['longitude'];
        $point = new Point($latitude, $longitude);
        switch ($this->entity) {
            case City::ENTITY:
                $city = City::where('status', 'ACTIVE')
                    ->contains('polygon_spatial', $point)
                    ->first();
                return !($city == null);
                break;
            case Zone::ENTITY:
                $zone = Zone::where('status', 'ACTIVE')
                    ->contains('polygon_spatial', $point)
                    ->first();
                return !($zone == null);
                break;
            default:
                return false;
        }
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message = __('rules.not_coverage');
    }
}
