<?php

namespace Bogardo\Mailgun;

use Bogardo\Mailgun\Contracts\Mailgun as MailgunContract;
use Illuminate\Support\ServiceProvider;
use Mailgun\Mailgun as MailgunApi;
use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\Hydrator\NoopHydrator;

class MailgunServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configPath('mailgun.php') => config_path('mailgun.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app->make('config');




        /**
         * Register main Mailgun service
         */
        $this->app->bind('mailgun', function () use ($config) {
            $configurator = new HttpClientConfigurator();
//            $configurator->setEndpoint( $config->get( 'mailgun.api.endpoint' ) );
            $configurator->setApiKey( $config->get( 'mailgun.api_key' ) );
            $configurator->setDebug( false );
            $configurator->setHttpClient( $this->app->make( 'mailgun.client' ) );

            $mg = new MailgunApi( $configurator, new NoopHydrator());

//            $mg->setApiVersion($config->get('mailgun.api.version'));
//            $mg->setSslEnabled($config->get('mailgun.api.ssl', true));

            return new Service($mg, $this->app->make('view'), $config);
        });

        /**
         * Register the public Mailgun service
         */
        $this->app->bind('mailgun.public', function () use ($config) {
            $configurator = new HttpClientConfigurator();
//            $configurator->setEndpoint( $config->get( 'mailgun.api.endpoint' ) );
            $configurator->setApiKey( $config->get( 'mailgun.public_api_key' ) );
            $configurator->setDebug( false );
            $configurator->setHttpClient( $this->app->make( 'mailgun.client' ) );

            return new MailgunApi( $configurator, new NoopHydrator() );

        });

        $this->app->bind(MailgunContract::class, 'mailgun');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mailgun', 'mailgun.public', MailgunContract::class];
    }

    /**
     * Get the path to the config directory.
     *
     * @param string $file
     *
     * @return string
     */
    protected function configPath($file = '')
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $file;
    }
}
