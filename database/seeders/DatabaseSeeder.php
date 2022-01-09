<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        
        DB::table('users')->insert([
            'name' => $faker->name,
            'email' => 'test1@gmail.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => $faker->name,
            'email' => 'test2@gmail.com',
            'password' => Hash::make('password'),
        ]);
        DB::table("products")->insert([
            'name' => $faker->name,
            'price' => 100000,
            'description' => 'est description',
        ]);
        DB::table("products")->insert([
            'name' => $faker->name,
            'price' => 150000,
            'description' => 'est description',
        ]);

        DB::table("inventories")->insert([
            'product_id' => 1,
            'stock' => 10,
        ]);
        DB::table("inventories")->insert([
            'product_id' => 2,
            'stock' => 10,
        ]);
        
    }
}
