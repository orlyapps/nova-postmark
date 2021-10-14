<?php

namespace Orlyapps\NovaPostmark\Nova\Actions;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Orlyapps\NovaWorkflow\Actions\WorkflowAction;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Orlyapps\NovaPostmark\Mail\LetterMailable;
use Orlyapps\NovaPostmark\Models\Letter;
use Orlyapps\NovaPostmark\Rules\Delimited;

class SendByMail extends WorkflowAction
{
    use InteractsWithQueue, Queueable;

    public $name = 'Per E-Mail versenden';

    /**
     * Get the URI key for the action.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'send-by-email';
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
            ->withStationery(true)
            ->save();

        /**
         * E-Mail Anhang speichern
         */
        $attachment = collect();
        $attachment->push(new UploadedFile($pdfFile, \Str::slug($letter->subject, '-') . '.pdf', 'application/pdf'));

        foreach ($fields->toArray() as $key => $item) {
            if (strpos($key, 'file') !== false) {
                $attachment->push($item);
            }
        }
        $mail = new LetterMailable($letter, $attachment);
        $mail->from($fields->from);
        \Mail::to($fields->to)->send($mail);

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
        $id = request('resourceId') ?? request('resources');
        $letter = Letter::find($id);
        if (!$letter) {
            return [];
        }
        return [
            ...parent::fields(),
            Text::make(__('From'))
                ->default(\Auth::user()->email)
                ->rules('required', 'email')
                ->suggestions([
                    \Auth::user()->email,
                ]),
            Text::make(__('To'))
                ->rules('required', 'email')
                ->default($letter->receiver->receiver_email[0])
                ->suggestions($letter->receiver->receiver_email),
            Text::make(__('CC'), 'cc')
                ->rules(new Delimited('email'))
                ->help(__('Separate multiple email addresses with comma')),
            File::make(__('Attachment'), 'file_1'),
            File::make('', 'file_2'),
            File::make('', 'file_3')
        ];
    }
}
