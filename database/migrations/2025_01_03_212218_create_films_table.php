<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_url');
            $table->string('trailer_url')->nullable();
            $table->string('movie_url');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('films');
    }
};
