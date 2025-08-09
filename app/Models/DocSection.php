<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocSection extends Model
{
    protected $fillable = ['title', 'slug', 'icon', 'ordem'];

    public function articles()
    {
        return $this->hasMany(DocArticle::class, 'section_id')->orderBy('ordem');
    }
}
