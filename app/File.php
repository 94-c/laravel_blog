<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['type', 'file', 'origin_file', 'extension', 'size'];

    /** relationShip */

    public function fileable()
    {
        return $this->morphTo();
    }
}
