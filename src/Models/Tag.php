<?php

namespace Napso\Lunytags\Models;

use App\Page;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function pages()
    {
        return $this->morphedByMany(Page::class, 'taggable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


}
