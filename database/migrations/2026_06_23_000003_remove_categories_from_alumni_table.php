<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $columns = array_values(array_filter([
            Schema::hasColumn('alumni', 'article_category1_id') ? 'article_category1_id' : null,
            Schema::hasColumn('alumni', 'article_category2_id') ? 'article_category2_id' : null,
        ]));

        if ($columns) {
            Schema::table('alumni', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    public function down()
    {
        Schema::table('alumni', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni', 'article_category1_id')) {
                $table->integer('article_category1_id')->nullable();
            }
            if (!Schema::hasColumn('alumni', 'article_category2_id')) {
                $table->integer('article_category2_id')->nullable();
            }
        });
    }
};
