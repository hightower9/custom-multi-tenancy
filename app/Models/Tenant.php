<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Tenant extends Model
{
    use HasFactory;

    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'domain',
        'database'
    ];

    /**
     * Configure
     */
    public function configure()
    {
        config([
            'database.connections.tenant.database' => $this->database,
            'cache.prefix' => $this->id,
            // Uncomment if using sessions
            'session.files' => storage_path('framework/sessions/'.$this->id),
        ]);

        DB::purge('tenant');

        app('cache')->purge(config('cache.default'));

        // try to comment the below 2 lines and see if it works
        DB::reconnect('tenant');  //

        Schema::connection('tenant')->getConnection()->reconnect();  //

        return $this;
    }

    /**
     * Use
     */
    public function use()
    {
        app()->forgetInstance('tenant');

        app()->instance('tenant', $this);

        return $this;
    }
}