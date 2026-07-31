<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('learning_center_banners')) {
            Schema::create('learning_center_banners', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('image_url', 500)->nullable();
                $table->enum('status', ['on', 'off'])->default('on');
                $table->timestamps();
            });
        }

        $hasBanner = DB::table('learning_center_banners')->where('id', 1)->exists();

        if ($hasBanner) {
            DB::table('learning_center_banners')
                ->where('id', 1)
                ->update([
                    'status' => 'on',
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('learning_center_banners')->insert([
                'id' => 1,
                'status' => 'on',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!Schema::hasTable('tb_menu')) {
            return;
        }

        $menuId = DB::table('tb_menu')
            ->where('url', 'webpanel.learning-center')
            ->value('id');

        if (!$menuId) {
            $menuId = DB::table('tb_menu')->insertGetId([
                '_id' => null,
                'name' => 'ศูนย์การเรียนรู้',
                'url' => 'webpanel.learning-center',
                'icon' => 'ki-duotone ki-book-open fs-2',
                'position' => 'main',
                'sort' => 9,
                'status' => 'on',
                'delete_status' => 'off',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('tb_menu')
                ->where('id', $menuId)
                ->update([
                    'name' => 'ศูนย์การเรียนรู้',
                    'status' => 'on',
                    'updated_at' => now(),
                ]);
        }

        if (!Schema::hasTable('tb_role_list')) {
            return;
        }

        $studyFurtherMenuId = DB::table('tb_menu')
            ->where('url', 'webpanel.studyfurther')
            ->value('id');

        $permissions = $studyFurtherMenuId
            ? DB::table('tb_role_list')->where('menu_id', $studyFurtherMenuId)->get()
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
        if (Schema::hasTable('tb_menu')) {
            $menuId = DB::table('tb_menu')
                ->where('url', 'webpanel.learning-center')
                ->value('id');

            if ($menuId && Schema::hasTable('tb_role_list')) {
                DB::table('tb_role_list')->where('menu_id', $menuId)->delete();
            }

            if ($menuId) {
                DB::table('tb_menu')->where('id', $menuId)->delete();
            }
        }

        Schema::dropIfExists('learning_center_banners');
    }
};
