<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkIdToGeneralNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('general_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('work_id')->nullable()->after('content_image');
            $table->index('work_id');
        });
    }

    public function down()
    {
        Schema::table('general_notifications', function (Blueprint $table) {
            $table->dropIndex(['work_id']);
            $table->dropColumn('work_id');
        });
    }
}
