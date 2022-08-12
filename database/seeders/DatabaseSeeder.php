<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\SeederUser::class);
        $this->call(\Database\Seeders\SeederAdmin::class);

        // GameMode
        $this->call(\Database\Seeders\SeederGameModeGuildWar::class);
        $this->call(\Database\Seeders\SeederGameModeStarExpedition::class);
        $this->call(\Database\Seeders\SeederGameModePeriod::class);

        // Guild
        $this->call(\Database\Seeders\SeederGuild::class);
        $this->call(\Database\Seeders\SeederAssociation::class);
    }
}
