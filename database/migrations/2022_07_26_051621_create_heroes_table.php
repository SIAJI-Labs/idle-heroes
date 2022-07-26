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
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('faction_id');
            $table->unsignedBigInteger('class_id');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->timestamps();

            $table->foreign('faction_id')
                ->references('id')
                ->on('hero_factions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('class_id')
                ->references('id')
                ->on('hero_classes')
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
        Schema::dropIfExists('heroes');
    }
};
