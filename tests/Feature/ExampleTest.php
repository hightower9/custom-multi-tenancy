<?php

namespace Tests\Feature;

use App\Models\Landlord\Tenant;
use App\Models\Tenant\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.landlord' => [
                'driver' => 'sqlite',
                'database' => ':memory:'
            ],

            'database.connections.tenant' => [
                'driver' => 'sqlite',
                'database' => ':memory:'
            ]
        ]);

        $this->artisan('migrate --database=landlord --path=database/migrations/landlord');
        $this->artisan('migrate --database=tenant');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_returns_current_tenant_and_list_of_all_its_users()
    {
        $tenant = Tenant::factory()->create();

        $tenant->use();

        User::factory()->count(4)->create();

        $response = $this->getJson('/users');

        $response->assertJsonCount(4, 'users');

        $response->assertJsonFragment([
            'name' => $tenant->name,
            'domain' => $tenant->domain,
        ]);
    }
}
