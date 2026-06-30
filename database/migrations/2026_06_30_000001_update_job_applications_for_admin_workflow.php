<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateJobApplicationsForAdminWorkflow extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE job_applications MODIFY status ENUM('new','reviewing','interview','passed','failed','pending','approved','rejected','completed','cancelled') NULL DEFAULT 'new'");

        DB::table('job_applications')->where('status', 'pending')->update(['status' => 'new']);
        DB::table('job_applications')->where('status', 'approved')->update(['status' => 'passed']);
        DB::table('job_applications')->whereIn('status', ['rejected', 'cancelled'])->update(['status' => 'failed']);
        DB::table('job_applications')->where('status', 'completed')->update(['status' => 'passed']);

        DB::statement("ALTER TABLE job_applications MODIFY status ENUM('new','reviewing','interview','passed','failed') NULL DEFAULT 'new'");

        Schema::table('job_applications', function (Blueprint $table) {
            $table->date('interview_date')->nullable()->after('status');
            $table->string('interview_time')->nullable()->after('interview_date');
            $table->string('interview_location')->nullable()->after('interview_time');
            $table->text('hr_note')->nullable()->after('interview_location');
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'interview_date',
                'interview_time',
                'interview_location',
                'hr_note',
            ]);
        });

        DB::statement("ALTER TABLE job_applications MODIFY status ENUM('new','reviewing','interview','passed','failed','pending','approved','rejected','completed','cancelled') NULL DEFAULT 'pending'");

        DB::table('job_applications')->where('status', 'new')->update(['status' => 'pending']);
        DB::table('job_applications')->where('status', 'reviewing')->update(['status' => 'pending']);
        DB::table('job_applications')->where('status', 'passed')->update(['status' => 'approved']);
        DB::table('job_applications')->where('status', 'failed')->update(['status' => 'rejected']);

        DB::statement("ALTER TABLE job_applications MODIFY status ENUM('pending','interview','approved','rejected','completed','cancelled') NULL DEFAULT 'pending'");
    }
}
