<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
  public function evaluations()
  {
   return $this->belongsToMany('App\Evaluation', 'Candidate__evaluations');
  }

  public function People()
  {
    return $this->morphOne('App\People', 'peopleable');
  }

}
