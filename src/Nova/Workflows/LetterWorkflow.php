<?php

namespace Orlyapps\NovaPostmark\Nova\Workflows;

use Orlyapps\NovaPostmark\Models\Letter;
use Orlyapps\NovaPostmark\Nova\Actions\SendByMail;
use Orlyapps\NovaPostmark\Nova\Actions\SendByPost;
use Orlyapps\NovaWorkflow\Models\Place;
use Orlyapps\NovaWorkflow\Models\Transition;
use Orlyapps\NovaWorkflow\Models\WorkflowDefinition;

class LetterWorkflow extends WorkflowDefinition
{
    public $name = 'letter';
    public $initialPlace = 'draft';


    /**
     * The class the workflow corresponds to.
     *
     * @var string
     */
    public $supports = [Letter::class];

    public function places()
    {
        return [
            Place::make(__('Draft'), 'draft')->color('orange'),
            Place::make(__('Sent'), 'sent')->color('green'),
        ];
    }

    public function transitions()
    {
        return [
            Transition::make(__('Send by post'), 'print')->from(['draft', 'sent'])->to('sent')->action(new SendByPost()),
            Transition::make(__('Send by e-mail'), 'mail')->from(['draft', 'sent'])->to('sent')->action(new SendByMail()),
        ];
    }

    public function users($invoice, $placeName)
    {
        return collect();
    }
}
