<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Seeder;

final class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todo::factory()->count(10)->create();
    }
}
