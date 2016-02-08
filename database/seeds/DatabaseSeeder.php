<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UsersSeeder::class);
        $this->call(ShippingRulesSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(EdiblesSeeder::class);

        Model::reguard();
    }
}
