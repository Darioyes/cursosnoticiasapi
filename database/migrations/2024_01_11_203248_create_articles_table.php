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
            $table->string('image',100)->nullable();
            //relacion con la tabla news
            $table->timestamps();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade')->onUpdate('cascade');
            //relacion con la tabla article_images
            $table->foreignId('article_image_id')->nullable()->constrained('article_images')->onDelete('cascade')->onUpdate('cascade');
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
