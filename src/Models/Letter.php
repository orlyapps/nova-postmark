<?php

namespace Orlyapps\NovaPostmark\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orlyapps\NovaPostmark\StringCompiler;
use Orlyapps\Printable\Printable;

class Letter extends Model
{
    use HasFactory;
    use Printable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'text' => 'object',
    ];

    public function stationeryPdf()
    {
        return config('nova-postmark.stationery');
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
