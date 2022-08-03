<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('star_expedition_participation_progress', 'timezone_offset')) {
            Schema::table('star_expedition_participation_progress', function (Blueprint $table) {
                $table->string('timezone_offset')->nullable()->after('value');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('star_expedition_participation_progress', 'timezone_offset')) {
            Schema::table('star_expedition_participation_progress', function (Blueprint $table) {
                $table->dropColumn('timezone_offset');
            });
        }
    }
};
