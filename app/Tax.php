<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
  public function taxdetails()
  {
    return $this->hasMany('App\TaxDetail');
  }

}
