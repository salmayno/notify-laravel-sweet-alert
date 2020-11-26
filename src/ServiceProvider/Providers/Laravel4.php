<?php

namespace Notify\Laravel\SweetAlert\ServiceProvider\Providers;

use Illuminate\Foundation\Application;
use Notify\Laravel\SweetAlert\NotifySweetAlertServiceProvider;

final class Laravel4 extends Laravel
{
    public function shouldBeUsed()
    {
        return $this->app instanceof Application && 0 === strpos(Application::VERSION, '4.');
    }

    public function publishConfig(NotifySweetAlertServiceProvider $provider)
    {
        $provider->package('php-notify/notify-laravel-sweet_alert', 'notify_sweet_alert', __DIR__.'/../../../resources');
    }

    public function mergeConfigFromSweetAlert()
    {
        $notifyConfig = $this->app['config']->get('notify::config.adapters.sweet_alert', array());

        $sweet_alertConfig = $this->app['config']->get('notify_sweet_alert::config', array());

        $this->app['config']->set('notify::config.adapters.sweet_alert', array_merge($sweet_alertConfig, $notifyConfig));
    }
}
