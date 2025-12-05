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
        Schema::create('profiles', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('first_name');
            $blueprint->string('last_name');
            $blueprint->string('birthday');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained()->cascadeOnDelete()->restrictOnDelete();
            // $blueprint->foreignId('user_id')->constrained()->restrictOnDelete();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
