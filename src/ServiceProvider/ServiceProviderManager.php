<?php

namespace Notify\Laravel\SweetAlert\ServiceProvider;

use Notify\Laravel\SweetAlert\NotifySweetAlertServiceProvider;
use Notify\Laravel\SweetAlert\ServiceProvider\Providers\ServiceProviderInterface;

final class ServiceProviderManager
{
    private $provider;

    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = array(
        'Notify\Laravel\SweetAlert\ServiceProvider\Providers\Laravel4',
        'Notify\Laravel\SweetAlert\ServiceProvider\Providers\Laravel',
        'Notify\Laravel\SweetAlert\ServiceProvider\Providers\Lumen',
    );

    private $notifyServiceProvider;

    public function __construct(NotifySweetAlertServiceProvider $notifyServiceProvider)
    {
        $this->notifyServiceProvider = $notifyServiceProvider;
    }

    public function boot()
    {
        $provider = $this->resolveServiceProvider();

        $provider->publishConfig($this->notifyServiceProvider);
        $provider->mergeConfigFromSweetAlert();
    }

    public function register()
    {
        $provider = $this->resolveServiceProvider();
        $provider->registerNotifySweetAlertServices();
    }

    /**
     * @return ServiceProviderInterface
     */
    private function resolveServiceProvider()
    {
        if ($this->provider instanceof ServiceProviderInterface) {
            return $this->provider;
        }

        foreach ($this->providers as $providerClass) {
            $provider = new $providerClass($this->notifyServiceProvider->getApplication());

            if ($provider->shouldBeUsed()) {
                return $this->provider = $provider;
            }
        }

        throw new \InvalidArgumentException('Service Provider not found.');
    }
}
