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
        Schema::create('spr_user_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state',2);
            $table->string('zip', 20);
            $table->string('country', 3);
            $table->timestamp('created_at')->useCurrent();
        });
    }
};
