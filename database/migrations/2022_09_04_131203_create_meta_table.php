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
        Schema::create('meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->ipAddress()->nullable()->default(null);
            $table->string('user_agent')->nullable()->default(null);
            $table->string('fingerprint')->nullable()->default(null);
            $table->integer('content_size')->nullable()->default(null);
        });

        Schema::table('meta', function ($table) {
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta');
    }
};
