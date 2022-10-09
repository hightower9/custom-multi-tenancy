<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LandlordMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landlord:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the tables in landlord';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Artisan::call('migrate --database='.config('database.default').' --path=database/migrations/landlord');
    }
}