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
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Titre facultatif
            $table->text('description')->nullable(); // Description facultative
            $table->string('file_path'); // Le chemin d'accès au fichier (ex: 'galleries/photo-123.jpg')
            $table->enum('media_type', ['image', 'video']); // Type de média
            $table->unsignedInteger('sort_order')->default(0); // Ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
