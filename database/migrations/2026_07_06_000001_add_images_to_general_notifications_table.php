<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToGeneralNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('general_notifications', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('detail');
            $table->string('content_image')->nullable()->after('cover_image');
        });
    }

    public function down()
    {
        Schema::table('general_notifications', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image',
                'content_image',
            ]);
        });
    }
}
