<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    
    public function translations()
    {
        return $this->hasMany('App\Translation');
    }

}
