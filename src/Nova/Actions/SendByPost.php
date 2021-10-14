<?php

namespace Orlyapps\NovaPostmark\Nova\Actions;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Orlyapps\NovaWorkflow\Actions\WorkflowAction;

use Laravel\Nova\Fields\Boolean;

class SendByPost extends WorkflowAction
{
    use InteractsWithQueue, Queueable;

    public $name = 'Per Post versenden';

    /**
     * Get the URI key for the action.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'send-by-post';
    }

    /**
     * Indicates if this action is only available on the resource detail view.
     *
     * @var bool
     */
    public $onlyOnDetail = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $letter = $models->first();
        $pdfFile = $letter->print()
            ->user(\Auth::user())
            ->withStationery($fields->withStationery)
            ->save();

        $letter->update(['send_at' => Carbon::now()]);
        parent::handle($fields, $models);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [

            Boolean::make(__('Stationery'), 'withStationery')
                ->help(__('If the option is enabled, the PDF will be created with stationery..')),
        ];
    }
}
