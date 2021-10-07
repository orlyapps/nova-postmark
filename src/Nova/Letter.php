<?php

namespace Orlyapps\NovaPostmark\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Orlyapps\NovaPostmark\Nova\Actions\Preview;
use Orlyapps\NovaTexteditor\Nova\Fields\TextEditor;

class Letter extends Resource
{
    /**
     * Sort Index (Custom)
     *
     * @var int
     */
    public static function index()
    {
        return 99;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Orlyapps\NovaPostmark\Models\Letter::class;

    /**
     * The resource group name
     *
     * @var string
     */
    public static $group = 'Einstellungen';

    /**
     * Indicates if the resoruce should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * Get the icon for the navigation
     *
     * @return string
     */
    public static function icon()
    {
        return 'keyboard';
    }

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Letters');
    }

    /**
     * Get the displayble singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Letter');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'subject';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'subject',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            MorphTo::make(__('Receiver'))->types(config('nova-postmark.receiver')),
            Text::make(__('Subject'))
                ->sortable()
                ->rules('required'),
            TextEditor::make(__('Text'))
                ->hideFromIndex()
                ->blocks(['signature' => 'Signatur', 'salutation' => 'Anrede']),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Preview::make(),
        ];
    }
}
