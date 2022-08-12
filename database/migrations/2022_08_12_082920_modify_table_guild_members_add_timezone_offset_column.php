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
        if (!Schema::hasColumn('guild_members', 'timezone_offset')) {
            Schema::table('guild_members', function (Blueprint $table) {
                $table->string('timezone_offset')->nullable()->after('out');
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
        if (Schema::hasColumn('guild_members', 'timezone_offset')) {
            Schema::table('guild_members', function (Blueprint $table) {
                $table->dropColumn('timezone_offset');
            });
        }
    }
};
