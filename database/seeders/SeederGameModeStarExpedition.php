<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederGameModeStarExpedition extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\StarExpeditionParticipationProgress::truncate();
        \App\Models\StarExpeditionParticipation::truncate();
        \App\Models\StarExpedition::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
