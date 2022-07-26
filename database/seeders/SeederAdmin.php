<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Admin::truncate();
        Schema::enableForeignKeyConstraints();
        $faker = \Faker\Factory::create();

        \App\Models\Admin::create([
            'name' => 'Dwi Aji',
            'username' => 'dwiaji',
            'email' => 'dwiaji.personal@gmail.com',
            'password' => bcrypt('dwiaji'),
            'raw_password' => siaCryption('dwiaji', true),
        ]);
    }
}
