<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle',255);
            $table->text('entrance');
            $table->text('body_news');
            //relacion con la tabla news
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade')->onUpdate('cascade');
            //relacion con la tabla article_images
            $table->foreignId('article_image_id')->constrained('article_images')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
