<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('general_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('detail')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 20)->default('on');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_notifications');
    }
}
