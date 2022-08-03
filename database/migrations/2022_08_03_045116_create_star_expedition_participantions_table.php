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
        Schema::create('star_expedition_participations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('star_expedition_id');
            $table->unsignedBigInteger('guild_member_id');
            $table->timestamps();

            $table->foreign('star_expedition_id')
                ->references('id')
                ->on('star_expeditions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('guild_member_id')
                ->references('id')
                ->on('guild_members')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('star_expedition_participations');
    }
};
