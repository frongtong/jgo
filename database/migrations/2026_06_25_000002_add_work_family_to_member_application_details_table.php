<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkFamilyToMemberApplicationDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('member_application_details', function (Blueprint $table) {
            $table->json('work_family')->nullable()->after('language_training');
        });
    }

    public function down()
    {
        Schema::table('member_application_details', function (Blueprint $table) {
            $table->dropColumn('work_family');
        });
    }
}
