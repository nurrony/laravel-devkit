<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;

final class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Link::factory()->count(10)->create();
    }
}
