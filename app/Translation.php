<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public function language()
    {
        return $this->belongsTo('App\Language');
    }
    
    public function source()
    {
        return $this->belongsTo('App\Source');
    }
}
