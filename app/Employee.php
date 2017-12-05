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
    return $this->hasMany('App\Loan','employees_id');
  }

  public function pays()
  {
   return $this->belongsToMany('App\Pay', 'pays_employees')->withPivot('code_employee','baseSalary', 'days', 'bond', 'ISSS', 'pension', 'rent', 'loans', 'otherDiscounts', 'otherIncomes', 'total');
  }

  public function attendances(){
    return $this->belongsToMany('App\Attendence', 'attendance_employees', 'attendance_id', 'employee_id')->withPivot('id','hourIn','hourOut','forgiven','observation', 'code_employee');
   }
}
