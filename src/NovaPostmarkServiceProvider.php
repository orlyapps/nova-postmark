<?php

namespace Orlyapps\NovaPostmark;

use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Nova\Nova;
use Orlyapps\NovaPostmark\Models\Letter;
use Orlyapps\NovaPostmark\Nova\Workflows\LetterWorkflow;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            \Orlyapps\NovaPostmark\Nova\Letter::class,
        ]);

        Relation::morphMap([
            'letter' => Letter::class,
        ]);

        app('workflow')->add(new LetterWorkflow);


        Field::macro(
            'autofill',
            function (string $attribute = null) {
                $request = app(NovaRequest::class);

                $shouldAutofill = $request->isCreateOrAttachRequest()
                    && ($instance = $request->findParentModel());

                if ($shouldAutofill) {
                    $this->withMeta([
                        'value' => $instance->{$attribute ?? $this->attribute},
                    ]);
                }

                return $this;
            }
        );
    }
}
