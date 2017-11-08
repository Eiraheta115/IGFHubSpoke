<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxDetail extends Model
{
  public function taxes()
  {
      return $this->belongsTo('App\Tax');
  }

}
