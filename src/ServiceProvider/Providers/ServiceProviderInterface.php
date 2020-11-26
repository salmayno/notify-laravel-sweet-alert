<?php

namespace Notify\Laravel\SweetAlert\ServiceProvider\Providers;

use Notify\Laravel\SweetAlert\NotifySweetAlertServiceProvider;

interface ServiceProviderInterface
{
    public function shouldBeUsed();

    public function publishConfig(NotifySweetAlertServiceProvider $provider);

    public function registerNotifySweetAlertServices();

    public function mergeConfigFromSweetAlert();
}
