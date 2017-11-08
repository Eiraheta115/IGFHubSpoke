<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    public function policies()
    {
      return $this->belongsToMany('App\Policy', 'group__policies');
    }

    public function users()
    {
      return $this->hasMany('App\User');
    }
}
