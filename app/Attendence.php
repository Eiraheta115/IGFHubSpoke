<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    protected $table = 'attendances';

    public function employees(){
      return $this->belongsToMany('App\Employee', 'attendance_employees', 'attendance_id')->withPivot('date','checkType','forgiven','observation','code_employee');
     }
    }
