<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function sources()
    {
        return $this->hasMany('App\Source');
    }
    
    public function translations()
    {
        return $this->hasMany('App\Translation');
    }
}
