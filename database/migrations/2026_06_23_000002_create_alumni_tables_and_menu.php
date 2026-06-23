<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->string('cover_image_url', 500)->nullable();
            $table->mediumText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->enum('status', ['on', 'off'])->default('on');
            $table->integer('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->mediumText('meta_keyword')->nullable();
            $table->timestamps();
        });

        Schema::create('alumni_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alumni_id');
            $table->string('image_url', 500);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('alumni_id')->references('id')->on('alumni')->cascadeOnDelete();
        });

        $menuId = DB::table('tb_menu')->insertGetId([
            '_id' => null,
            'name' => 'รุ่นพี่ศิษย์เก่า',
            'url' => 'webpanel.alumni',
            'icon' => 'ki-duotone ki-profile-user fs-2',
            'position' => 'main',
            'sort' => 7,
            'status' => 'on',
            'delete_status' => 'off',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $articleMenuId = DB::table('tb_menu')->where('url', 'webpanel.article')->value('id');
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
        $menuId = DB::table('tb_menu')->where('url', 'webpanel.alumni')->value('id');
        if ($menuId) {
            DB::table('tb_role_list')->where('menu_id', $menuId)->delete();
            DB::table('tb_menu')->where('id', $menuId)->delete();
        }

        Schema::dropIfExists('alumni_banners');
        Schema::dropIfExists('alumni');
    }
};
