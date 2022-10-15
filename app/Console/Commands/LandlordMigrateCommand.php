<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

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
        $this->info('Started Migration');

        Artisan::call('migrate', ['--path' => 'database/migrations/landlord', '--database' => config('database.default')], new ConsoleOutput);
        $this->info(Artisan::output());

        $this->info('Migration Successfully');
    }
}