<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->unsignedBigInteger('featured_image_id')->nullable();
            $table->unsignedBigInteger('meta_image_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->enum('status', ['draft', 'published', 'private'])->default('draft');
            $table->boolean('allow_comments')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();

            $table->foreign('featured_image_id')->references('id')->on('assets')->onDelete('set null');
            $table->foreign('meta_image_id')->references('id')->on('assets')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
