<?php

declare(strict_types=1);

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
        Schema::create('todos', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->text('description')->nullable();
            // Tips: 1: Set Default DB Column Values in Eloquent Model
            $blueprint->boolean('is_completed')->default(false);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
