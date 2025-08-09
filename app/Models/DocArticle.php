<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocArticle extends Model
{
    protected $fillable = ['section_id', 'title', 'slug', 'content', 'ordem'];

    public function section()
    {
        return $this->belongsTo(DocSection::class);
    }
}
