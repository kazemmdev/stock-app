<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all products stock';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Product::all()->each->track();

        $this->info('All done!');
    }
}
