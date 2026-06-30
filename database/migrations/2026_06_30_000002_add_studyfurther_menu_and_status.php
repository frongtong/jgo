<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('studyfurther')) {
            Schema::table('studyfurther', function (Blueprint $table) {
                if (!Schema::hasColumn('studyfurther', 'status')) {
                    $table->enum('status', ['on', 'off'])->default('on')->after('description');
                }

                if (!Schema::hasColumn('studyfurther', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('status');
                }
            });
        }

        if (!Schema::hasTable('tb_menu')) {
            return;
        }

        $menuId = DB::table('tb_menu')
            ->where('url', 'webpanel.studyfurther')
            ->value('id');

        if (!$menuId) {
            $menuId = DB::table('tb_menu')->insertGetId([
                '_id' => null,
                'name' => 'โอกาสเรียนต่อ',
                'url' => 'webpanel.studyfurther',
                'icon' => 'ki-duotone ki-book-open fs-2',
                'position' => 'main',
                'sort' => 8,
                'status' => 'on',
                'delete_status' => 'off',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('tb_menu')
                ->where('id', $menuId)
                ->update([
                    'name' => 'โอกาสเรียนต่อ',
                    'status' => 'on',
                    'updated_at' => now(),
                ]);
        }

        if (!Schema::hasTable('tb_role_list')) {
            return;
        }

        $articleMenuId = DB::table('tb_menu')
            ->where('url', 'webpanel.article')
            ->value('id');

        $permissions = $articleMenuId
            ? DB::table('tb_role_list')->where('menu_id', $articleMenuId)->get()
            : collect();

        if ($permissions->isEmpty()) {
            $permissions = collect([(object) [
                'role_id' => 1,
                'read' => 'on',
                'add' => 'on',
                'edit' => 'on',
                'delete' => 'on',
            ]]);
        }

        foreach ($permissions as $permission) {
            $exists = DB::table('tb_role_list')
                ->where('role_id', $permission->role_id)
                ->where('menu_id', $menuId)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('tb_role_list')->insert([
                'role_id' => $permission->role_id,
                'menu_id' => $menuId,
                'read' => $permission->read ?? 'on',
                'add' => $permission->add ?? 'on',
                'edit' => $permission->edit ?? 'on',
                'delete' => $permission->delete ?? 'on',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        if (!Schema::hasTable('tb_menu')) {
            return;
        }

        $menuId = DB::table('tb_menu')
            ->where('url', 'webpanel.studyfurther')
            ->value('id');

        if ($menuId && Schema::hasTable('tb_role_list')) {
            DB::table('tb_role_list')->where('menu_id', $menuId)->delete();
        }

        if ($menuId) {
            DB::table('tb_menu')->where('id', $menuId)->delete();
        }
    }
};
