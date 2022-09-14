<?php

namespace Brendfoni\ProductRating;

use Brendfoni\ProductRating\Facade\BrendfoniProductRatingFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class ProductRatingServiceProvider extends ServiceProvider
{
    protected $policies = [
        //        Autocomplete::class => AutocompletePolicy::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('BrendfoniProductRating', BrendfoniProductRatingFacade::class);

        $this->app->singleton('brendfoniproductrating', function () {
            return new BrendfoniProductRating();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brendfoniproductRating');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/'),
        ]);
        
        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('migrations'),
        ], 'product_rating_migrations');
    }
}
