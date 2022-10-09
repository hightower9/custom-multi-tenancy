<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class TenantMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate {tenant?} {--fresh} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the tables in each tenant';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('tenant')){
            $this->migrate(
                Tenant::find($this->argument('tenant'))
            );
        }
        else{
            Tenant::all()->each(
                fn($tenant) => $this->migrate($tenant)
            );
        }
    }

    /**
     * Migrate the tables.
     *
     * @param Tenant $tenant
     * @return void
     */
    public function migrate(Tenant $tenant)
    {
        $tenant->configure()->use();

        $this->line('');
        $this->line('----------------------------------------------------');
        $this->info("Migrating Tenant #{$tenant->id} ({$tenant->name})");
        $this->line('----------------------------------------------------');

        $options = ['--force' => true];

        if($this->option('seed')){
            $options['--seed'] = true;
        }

        $this->call(
            $this->option('fresh') ? 'migrate:fresh' : 'migrate', 
            $options
        );
    }
}