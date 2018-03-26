<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $incrementing = false;
    
    public function translations()
    {
        return $this->hasMany('App\Translation');
    }
}
