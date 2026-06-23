<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_parent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('parent_id');
            $table->timestamps();

            $table->unique(['member_id', 'parent_id']);
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('members')->cascadeOnDelete();
        });

        $now = now();

        DB::table('members')
            ->whereNotNull('parent_id')
            ->select(['id', 'parent_id'])
            ->orderBy('id')
            ->chunkById(500, function ($members) use ($now) {
                $rows = $members->map(function ($member) use ($now) {
                    return [
                        'member_id' => $member->id,
                        'parent_id' => $member->parent_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                })->all();

                DB::table('member_parent')->insertOrIgnore($rows);
            });
    }

    public function down()
    {
        Schema::dropIfExists('member_parent');
    }
};
