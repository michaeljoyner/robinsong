<?php

use App\Shipping\ShippingLocation;
use App\Shipping\WeightClass;
use Illuminate\Database\Seeder;

class ShippingRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uk = factory(ShippingLocation::class)->create(['name' => 'United Kingdom']);
        $uk->addWeightClass(['weight_limit' => 100, 'price' => 700]);
        $uk->addWeightClass(['weight_limit' => 200, 'price' => 900]);
        $uk->addWeightClass(['weight_limit' => 300, 'price' => 1100]);
        $uk->addWeightClass(['weight_limit' => 9999, 'price' => 1500]);

        $int = factory(ShippingLocation::class)->create(['name' => 'International']);
        $int->addWeightClass(['weight_limit' => 100, 'price' => 900]);
        $int->addWeightClass(['weight_limit' => 200, 'price' => 1100]);
        $int->addWeightClass(['weight_limit' => 300, 'price' => 1300]);
        $int->addWeightClass(['weight_limit' => 9999, 'price' => 1700]);
    }
}
