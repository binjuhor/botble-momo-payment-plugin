<?php

namespace Binjuhor\MoMo\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class MoMoServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/momo')
                ->loadAndPublishConfigurations(['general'])
                ->loadHelpers()
                ->loadRoutes()
                ->loadAndPublishViews()
                ->loadAndPublishTranslations()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);
        }
    }
}
