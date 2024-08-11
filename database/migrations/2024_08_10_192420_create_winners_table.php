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
        Schema::create('spr_winners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('User ID');
            $table->foreign('user_id')->references('id')->on('spr_users')->onDelete('CASCADE');

            $table->integer('points');
            $table->dateTime('created_at')->nullable();

        });
    }
};
