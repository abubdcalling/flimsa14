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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->longText('title');           // Title of the post
            $table->longText('slug');  // Slug for SEO-friendly URL
            $table->string('image')->nullable(); // Image URL or path
            $table->longText('details');       // Details or content of the post
            $table->json('tags')->nullable(); // JSON array for tags
            $table->json('keywords')->nullable(); // JSON array for keywords
            $table->longText('meta_description')->nullable(); // Meta description for SEO
            $table->longText('meta_title')->nullable(); // Meta title for SEO
            $table->boolean('publish')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
