<?php

namespace Orlyapps\NovaPostmark\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Orlyapps\NovaPostmark\Models\Letter;

class LetterMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $letter = null;
    public $attachment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Letter $letter, Collection $attachment)
    {
        $this->letter = $letter;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject($this->letter->subject)
            ->markdown('nova-postmark::mail', ['letter' => $this->letter]);
        /*
        $this->attachment->each(fn ($file) => $mail->attach($file, [
            'as' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
        ]));
        */
        return $mail;
    }
}
