<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryType extends Model
{
  public function employees()
  {
    return $this->hasOne('App\Employee', 'salarytype_id');
  }

}
