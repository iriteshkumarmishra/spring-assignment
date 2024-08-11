<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spr_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->max(255);
            $table->integer('age')->max(100); // considering the user age would be max 100 years
            $table->integer('points')->default(0); // keeping the default value to 0
            $table->unsignedInteger('address_id')->comment('User Address ID');
            $table->foreign('address_id')->references('id')->on('spr_user_addresses')->onDelete('CASCADE');
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });
    }
};
