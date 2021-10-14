<?php

namespace Orlyapps\NovaPostmark\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orlyapps\NovaPostmark\StringCompiler;
use Orlyapps\NovaWorkflow\Traits\HasWorkflow;
use Orlyapps\Printable\Printable;

class Letter extends Model
{
    use HasFactory, Printable, HasWorkflow;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'text' => 'object',
        'send_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($letter) {
            $letter->user()->associate(auth()->user());
        });
    }

    public function stationeryPdf()
    {
        return config('nova-postmark.stationery');
    }
    public function user()
    {
        return $this->belongsTo(config('nova-postmark.user_class'));
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    public function printView()
    {
        return 'nova-postmark::layout';
    }

    public function getHtmlAttribute()
    {
        $renderer = new \Orlyapps\NovaTexteditor\Renderer();
        $html = $renderer->render($this->text);

        $html = StringCompiler::compile($html, [
            'user' => \Auth::user(),
            'contact' => $this->receiver,
            'letter' => $this,
        ]);

        return $html;
    }
}
