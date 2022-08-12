<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederGameModeGuildWar extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\GuildWarParticipationPoint::truncate();
        \App\Models\GuildWarParticipation::truncate();
        \App\Models\GuildWar::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
