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
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ipAddress()->nullable()->default(null);
            $table->string('user_agent', 128)->nullable()->default(null);
            $table->string('fingerprint', 128)->nullable()->default(null);
            $table->integer('content_size')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('sent_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
};
