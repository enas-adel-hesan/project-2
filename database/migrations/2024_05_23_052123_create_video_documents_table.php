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
        Schema::create('video_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id');
          $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
          $table->string('file');
          $table->string('file_type');
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
        Schema::dropIfExists('video_documents');
    }
};
