<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
  public function taxdetails()
  {
    return $this->hasMany('App\TaxDetail', 'taxe_id');
  }

  public function employees()
  {
      return $this->hasOne('App\Employee', 'pensionType_id');
  }

}
