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
        Schema::create('hero_tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('hero_id');
            $table->unsignedBigInteger('tenant_hero_id');
            $table->enum('slot', ['slot_1', 'slot_2', 'slot_3', 'slot_4']);
            $table->timestamps();

            $table->foreign('hero_id')
                ->references('id')
                ->on('heroes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('tenant_hero_id')
                ->references('id')
                ->on('heroes')
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
        Schema::dropIfExists('hero_tenants');
    }
};
