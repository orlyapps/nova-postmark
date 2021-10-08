<?php

namespace Orlyapps\NovaPostmark\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orlyapps\NovaPostmark\StringCompiler;
use Orlyapps\NovaWorkflow\Traits\HasWorkflow;
use Orlyapps\Printable\Printable;

class Letter extends Model
{
<<<<<<< HEAD
    use HasFactory, Printable, HasWorkflow;
=======
    use HasFactory;
    use Printable;
>>>>>>> 3475f50ebef954ce4758e8af209dd341503fb612

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
