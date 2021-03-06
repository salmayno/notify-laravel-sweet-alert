<?php

namespace Notify\Laravel\SweetAlert\Tests;

use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app = null)
    {
        return array(
            'Notify\Laravel\NotifyServiceProvider',
            'Notify\Laravel\SweetAlert\NotifySweetAlertServiceProvider',
        );
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $separator = $this->isLaravel4() ? '::config' : '';

        $app['config']->set('session'.$separator.'.driver', 'array');
        $app['config']->set('notify'.$separator.'.stamps_middlewares', array());
        $app['config']->set('notify'.$separator.'.adapters', array(
            'sweet_alert' => array('scripts' => array('jquery.js'), 'styles' => array('styles.css'), 'options' => array()),
        ));
    }

    private function isLaravel4()
    {
        return 0 === strpos(Application::VERSION, '4.');
    }

    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        if (0 !== strpos(Application::VERSION, '4.0')) {
            return parent::createApplication();
        }

        $app = new Application;

        $app->detectEnvironment(array(
            'local' => array('your-machine-name'),
        ));

        $app->bindInstallPaths($this->getApplicationPaths());

        $app['env'] = 'testing';

        $app->instance('app', $app);

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);

        $config = new Config($app->getConfigLoader(), $app['env']);
        $app->instance('config', $config);
        $app->startExceptionHandling();

        if ($app->runningInConsole())
        {
            $app->setRequestForConsoleEnvironment();
        }

        date_default_timezone_set($this->getApplicationTimezone());

        $aliases = array_merge($this->getApplicationAliases(), $this->getPackageAliases());
        AliasLoader::getInstance($aliases)->register();

        Request::enableHttpMethodParameterOverride();

        $providers = array_merge($this->getApplicationProviders(), $this->getPackageProviders());
        $app->getProviderRepository()->load($app, $providers);

        $this->getEnvironmentSetUp($app);

        $app->boot();

        return $app;
    }
}
