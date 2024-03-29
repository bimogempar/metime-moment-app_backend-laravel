<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id');
            $table->string('client');
            $table->string('slug');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->integer('status');
            $table->string('phone_number');
            $table->string('thumbnail_img')->nullable();
            $table->string('folder_gdrive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
