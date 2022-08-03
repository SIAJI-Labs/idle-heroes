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
        Schema::create('star_expedition_participation_progress', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('star_expedition_participation_id');
            $table->string('key');
            $table->string('value');
            $table->timestamps();

            $table->foreign('star_expedition_participation_id', 'se_participation_id')
                ->references('id')
                ->on('star_expedition_participations')
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
        Schema::dropIfExists('star_expedition_participation_progress');
    }
};
