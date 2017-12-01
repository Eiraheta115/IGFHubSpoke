<?php

namespace App\Http\Controllers;
use App\Employee;
use App\Attendence;
use App\AttEmployee;
use Illuminate\Http\Request;
use Log;
class AttendenceController extends Controller
{
    public function generate(Request $request){
      $data= $request->json()->all();
      $attendance= new Attendence;
      $attendance->name=$data['name'];
      $attendance->type="mensual";
      $attendance->divison=1;
      // $array= $data['array'];

      $conditions=['active'=>true, 'salarytype_id'=>1];
      $employees=Employee::where($conditions)->get();

      $workdays = array();
      $type = CAL_GREGORIAN;
      $m=$month = date($data['month']); // Month ID, 1 through to 12.
      $y=$year = date($data['year']); // Year in 4 digit 2009 format.
      $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

      //loop through all days
      for ($i = 1; $i <= $day_count; $i++) {

        $date = $year.'/'.$month.'/'.$i; //format date
        $get_name = date('l', strtotime($date)); //get week day
          $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

        //if not a weekend add day to array
        if($day_name != 'Sun' && $day_name != 'Sat'){
            $workdays[] = date($y."-".$m."-".$i);
        }

      }
      $attendance->save();
      $type=["I","O"];
      for ($j=0; $j <count($workdays) ; $j++) {
        for ($i=0; $i <2 ; $i++) {
          foreach ($employees as $employee) {
            $attendance->employees()->attach($employee->id,['date'=>$workdays[$j], 'checkType'=>$type[$i], 'forgiven'=> false, 'observation'=>"Preguardado",'code_employee'=>$employee->code]);
          }
        }

      }
      return response()->json(['saved' => true], 201);
    }

    public function massUpdate($id, Request $request){
      $data= $request->json()->all();
      $array= $data['array'];
      $attendance= Attendence::find($id);

      foreach ($attendance->employees as $employee) {
        $pivotId=$employee->pivot->id;
        $emploAtt=AttEmployee::find($pivotId);

        $attTimestap=$employee->pivot->date;
        $code=$employee->pivot->code_employee;
        $attDaytime=explode(" ",$attTimestap);
        $attDay=$attDaytime[0];
        $attHour=$attDaytime[1];

        for ($i=0; $i <count($array) ; $i++) {
          $timestamp=$array[$i]['fecha'];
          $code_employee=$array[$i]['code'];
          $datetime = explode(" ",$timestamp);
          $hour=$datetime[1];
          $day=$datetime[0];
          if (strtotime($hour)>strtotime("12:00")) {
              $checkType="O";
          }else {
              $checkType="I";
          }
          if ($code==$code_employee && strtotime($attDay)==strtotime($day) && $employee->pivot->checkType==$checkType) {
              $emploAtt->date=$timestamp;
              $emploAtt->checkType=$checkType;
              $emploAtt->observation="Guardado";
              $emploAtt->forgiven=true;
              $emploAtt->save();
            //  $attendance->employees()->updateExistingPivot($employee->pivot->employee_id,['date'=>$timestamp,'checkType'=>$checkType]);
              $json1[]=['id_table'=>$employee->pivot->id, 'codeJ'=>$code_employee, 'codeBD'=>$code, 'dayJ'=>$timestamp, 'dayBD'=>$attDay];
          }else {
            $json2[]=['codeJ'=>$code_employee, 'codeBD'=>$code, 'dayJ'=>$timestamp, 'dayBD'=>$attDay];
          }
        }
      }
      return response()->json(['updated' => true], 200);
    }

    // public function employeeByMonth(Request $request){
    //   $data= $request->json()->all();
    //   $emploAtt=AttEmployee::where('date','like', $data['year'].'a%');
    // }

}
