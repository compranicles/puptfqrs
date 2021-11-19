<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertServiceConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_service_consultants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification');
            $table->foreignId('category');
            $table->foreignId('level');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('title', 500);
            $table->string('venue');
            $table->string('partner');
            $table->text('description');
            $table->foreignId('user_id');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_service_consultants');
    }
}
