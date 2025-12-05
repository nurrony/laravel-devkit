<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;

final class LinkNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new link';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $url = $this->ask('Link URL:');
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('Invalid URL. Exiting...');

            return 1;
        }

        $description = $this->ask('Link Description:');

        $this->info('New Link:');
        $this->info($url . ' - ' . $description);

        if ($this->confirm('Is this information correct?')) {
            $link = new Link;
            $link->url = $url;
            $link->description = $description;
            $link->save();
            $this->info('Saved.');
        }

        return 0;

    }
}
