<?php

namespace Visanduma\LaravelInventory\Commands;

use Illuminate\Console\Command;

class LaravelInventoryCommand extends Command
{
    public $signature = 'laravel-inventory';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
