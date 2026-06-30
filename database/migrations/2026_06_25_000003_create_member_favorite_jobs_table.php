<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFavoriteJobsTable extends Migration
{
    public function up()
    {
        Schema::create('member_favorite_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('job_id');
            $table->timestamps();

            $table->unique(['member_id', 'job_id']);
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
            $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_favorite_jobs');
    }
}
