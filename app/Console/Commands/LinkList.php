<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;

final class LinkList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all links';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $headers = ['id', 'url', 'description'];
        $links = Link::all(['id', 'url', 'description'])->toArray();
        $this->table($headers, $links);

        return 0;
    }
}
