<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vocabulary_items', function (Blueprint $table) {
            $table->id();
            $table->integer('vocabulary_id');
            $table->string('japanese_word');
            $table->string('reading')->nullable();
            $table->string('meaning_th');
            $table->text('example_japanese')->nullable();
            $table->text('example_reading')->nullable();
            $table->text('example_thai')->nullable();
            $table->string('word_audio_url', 500)->nullable();
            $table->string('example_audio_url', 500)->nullable();
            $table->enum('status', ['on', 'off'])->default('on');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('vocabulary_id')
                ->references('id')
                ->on('vocabulary')
                ->cascadeOnDelete();
            $table->index(['vocabulary_id', 'status', 'sort_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vocabulary_items');
    }
};
