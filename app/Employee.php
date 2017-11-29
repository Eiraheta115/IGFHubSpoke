<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  public function jobs()
  {
    return $this->hasOne('App\Job');
  }

  public function salaryTypes()
  {
    return $this->belongsTo('App\SalaryType', 'salarytype_id');
  }

  public function users()
  {
    return $this->hasOne('App\User');
  }

  public function taxes()
  {
    return $this->belongsTo('App\Tax', 'pensionType_id');
  }

  public function People()
  {
    return $this->morphOne('App\People', 'peopleable');
  }

  public function loans()
  {
    return $this->hasMany('App\Loan');
  }
}
