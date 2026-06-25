<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberApplicationDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('member_application_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->unique();
            $table->json('personal')->nullable();
            $table->json('education_extra')->nullable();
            $table->json('language_training')->nullable();
            $table->json('health')->nullable();
            $table->json('additional')->nullable();
            $table->json('responsibility')->nullable();
            $table->json('guarantor')->nullable();
            $table->json('goals')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_application_details');
    }
}
