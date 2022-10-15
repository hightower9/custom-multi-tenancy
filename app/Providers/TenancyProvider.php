<?php

namespace App\Providers;

use App\Models\Landlord\Tenant;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;

class TenancyProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRequests();

        $this->configureQueue();
    }

    /**
     * Configuration of Requests.
     *
     * @return void
     */
    public function configureRequests()
    {
        if (! $this->app->runningInConsole()) {
            $host = $this->app['request']->getHost();
            
            if(!empty($this->checkIfLandlordOrTenant($host))){
                Tenant::whereDomain($host)->firstOrFail()->configure()->use();
            }
        }
    }

    /**
     * Gets the Domain
     * 
     * @param string $domain
     * @return string
     */
    private function getDomain($domain)
    {
        return preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)
        ? $matches['domain'] : $domain;
    }

    /**
     * Gets the Subdomain
     * 
     * @param string $domain
     * @return string
     */
    public function getSubdomain($domain) : string
    {
        $subdomains = $domain;
        $domain = $this->getDomain($subdomains);

        return explode('.', rtrim(strstr($subdomains, $domain, true), '.'))[0];
    }

    /**
     * Checks if Landlord or Tenant
     * 
     * @param string $domain
     * @return string
     */
    public function checkIfLandlordOrTenant(string $host) : string
    {
        // Check in Localhost
        if(str_contains($host, 'localhost')) {
            $subdomain = explode('.', $host);

            return array_reverse($subdomain)[0] === 'localhost' && count($subdomain) >= 2 
            ? $subdomain[0] : '';
        }
        else{
            return $this->getSubdomain($host);
        }
    }

    /**
     * Configuration of Queues.
     *
     * @return void
     */
    public function configureQueue()
    {
        $this->app['queue']->createPayloadUsing(function () {
            return $this->app['tenant'] ? ['tenant_id' => $this->app['tenant']->id] : [];
        });

        $this->app['events']->listen(JobProcessing::class, function ($event) {
            if (isset($event->job->payload()['tenant_id'])) {
                Tenant::find($event->job->payload()['tenant_id'])->configure()->use();
            }
        });
    }
}