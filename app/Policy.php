<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    public function users()
    {
      return $this->belongsToMany('App\User', 'user__policies');
    }

    public function groups()
    {
      return $this->belongsToMany('App\Group', 'group__policies');
    }
}
