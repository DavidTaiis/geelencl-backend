<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BenefitTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('benefit_type')->delete();
        DB::table('benefit_type')->insert(
            [
                'id' => 1,
                'name' => 'Descuento en la orden',
                'type' => 'DISCOUNT_ORDER',
                'logic' => 'PERCENTAGE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
        DB::table('benefit_type')->insert(
            [
                'id' => 2,
                'name' => 'Gana puntos ',
                'type' => 'WIN_POINTS',
                'logic' => 'VALUE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
        DB::table('benefit_type')->insert(
            [
                'id' => 3,
                'name' => 'Entrega gratis',
                'type' => 'DELIVERY_FREE',
                'logic' => 'VALUE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
        DB::table('benefit_type')->insert(
            [
                'id' => 4,
                'name' => 'Descuento en producto',
                'type' => 'DISCOUNT_IN_PRODUCT',
                'logic' => 'PERCENTAGE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }
}
