<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    public function peopleable()
    {
      return $this->morphTo();
    }
}
