<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
  public function employees(){
    return $this->belongsToMany('App\Employee', 'pays_employees')->withPivot('code_employee','baseSalary', 'days', 'bond', 'ISSS', 'pension', 'rent', 'loans', 'otherDiscounts', 'otherIncomes', 'total');
   }
}
