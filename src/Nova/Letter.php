<?php

namespace Orlyapps\NovaPostmark\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Resource;
use Orlyapps\NovaPostmark\Nova\Actions\Preview;
use Orlyapps\NovaTexteditor\Nova\Fields\TextEditor;
use Orlyapps\NovaPostmark\Models\Letter as ModelsLetter;
use Orlyapps\NovaPostmark\Nova\Actions\SendByMail;
use Orlyapps\NovaPostmark\Nova\Actions\SendByPost;
use Orlyapps\NovaWorkflow\Actions\WorkflowAction;
use Orlyapps\NovaWorkflow\Cards\WorkflowCard;
use Orlyapps\NovaWorkflow\Fields\WorkflowBadge;
use Orlyapps\NovaWorkflow\Filters\StatusFilter;


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
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Write :resource', ['resource' => static::singularLabel()]);
    }

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
            WorkflowBadge::make('Status', ModelsLetter::class),
            BelongsTo::make(__('User'), 'user', config('nova-postmarkt.nova_user_class'))
                ->searchable()
                ->nullable()
                ->exceptOnForms(),
            MorphTo::make(__('Receiver'), 'receiver')
                ->types(config('nova-postmark.receiver'))
                ->nullable()
                ->searchable(),
            Textarea::make(__('Sender address'), 'sender_address')->alwaysShow()->withMeta([
                'value' => config('nova-postmark.sender_address'),
            ]),
            Textarea::make(__('Receiver address'), 'receiver_address')->alwaysShow()->autofill('receiver_address'),
            Textarea::make(__('Info'), 'info')->alwaysShow()->autofill('letter_info'),
            Text::make(__('Subject'), 'subject')
                ->sortable()
                ->rules('required'),
            Date::make(__('Sended at'), 'send_at')->exceptOnForms(),
            TextEditor::make(__('Text'), 'text')
                ->hideFromIndex()
                ->templateCategory('letter')
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
        return [
            WorkflowCard::make(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            StatusFilter::make($this)
        ];
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
            WorkflowAction::make(),
            SendByMail::make(),
            SendByPost::make(),
            Preview::make()
        ];
    }
}
