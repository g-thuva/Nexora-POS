<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_number')->unique();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->integer('estimated_duration')->nullable()->comment('Estimated duration in minutes');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('shop_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
