<?php

namespace Orlyapps\NovaPostmark;

use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Laravel\Nova\Nova;
use Orlyapps\NovaPostmark\Models\Letter;

class NovaPostmarkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('nova-postmark')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_nova-postmark_table');
    }
    public function bootingPackage()
    {
        Nova::resources([
            \Orlyapps\NovaPostmark\Nova\Letter::class
        ]);

        Relation::morphMap([
            'letter' => Letter::class
        ]);
    }
}
