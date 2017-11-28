<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
  public function loantypes()
  {
    return $this->hasOne('App\LoanTypes', 'loanType_id');
  }
  public function employees()
  {
    return $this->belongsTo('App\Employee');
  }
}
