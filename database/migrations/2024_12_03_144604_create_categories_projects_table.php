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
        Schema::create('categories_projects', function (Blueprint $table) {
            // pivot table dont need id
            $table->id();
            $table->foreignId('projects_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('categories_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            // look this part too:
            // $table->primary(['project_id', 'category_id']); // Composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_projects');
    }
};
