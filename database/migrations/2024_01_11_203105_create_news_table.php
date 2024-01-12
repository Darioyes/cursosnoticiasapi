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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('epigraph',500)->nullable();
            $table->string('title',255);
            $table->string('image',100)->nullable();
            $table->enum('content',['news','course']);
            //columna que indica con true o false si el articulo es destacado
            $table->boolean('featured')->default(false);
            //columna que indica con true o false si el articulo es visible
            $table->boolean('visible')->default(true);
            $table->timestamps();
            //relacion con la tabla categories_news
            $table->foreignId('category_news_id')->constrained('categories_news')->onDelete('cascade')->onUpdate('cascade');
            //relacion de la tabla news con la tabla categories_courses
            $table->foreignId('category_course_id')->nullable()->constrained('categories_courses')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
