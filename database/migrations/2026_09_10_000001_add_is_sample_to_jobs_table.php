<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('jobs', 'is_sample')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->boolean('is_sample')->default(false)->after('welfare');
            });
        }

        DB::statement('ALTER TABLE jobs MODIFY detail LONGTEXT NULL');
        DB::statement('ALTER TABLE jobs MODIFY welfare LONGTEXT NULL');
    }

    public function down()
    {
        if (Schema::hasColumn('jobs', 'is_sample')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->dropColumn('is_sample');
            });
        }
    }
};
