<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
  public function candidates()
  {
   return $this->belongsToMany('App\Candidate', 'candidate__evaluations');
  }

}
