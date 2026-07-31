<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCancelledStatusToJobApplications extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE job_applications MODIFY status ENUM('new','reviewing','interview','passed','failed','cancelled') NULL DEFAULT 'new'");
    }

    public function down()
    {
        DB::table('job_applications')
            ->where('status', 'cancelled')
            ->update(['status' => 'failed']);

        DB::statement("ALTER TABLE job_applications MODIFY status ENUM('new','reviewing','interview','passed','failed') NULL DEFAULT 'new'");
    }
}
