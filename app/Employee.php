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
    return $this->hasOne('App\SalaryType');
  }

  public function users()
  {
    return $this->hasOne('App\User');
  }

  public function People()
  {
    return $this->morphOne('App\People', 'peopleable');
  }
}
