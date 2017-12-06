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
      switch ($data['salarytype_id']) {
        case 1:
          $attendance->type="mensual";
          $attendance->divison=1;
          break;
        case 2:
          $attendance->type="quincenal";
          $attendance->divison=$data['divison'];
          break;
        }

      $conditions=['active'=>true, 'salarytype_id'=>$data['salarytype_id']];
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
      if ($data['salarytype_id']==2) {
        $limit=array_search($year . "-" . $month . "-15", $workdays);
        switch ($data['divison']) {
          case 1:
            $init=0;
            break;
          case 2:
            $init=$limit+1;
            $limit=count($workdays);
            break;
        }
      }else{
        $init=0;
        $limit=count($workdays);
      }
      for ($j=$init; $j <$limit ; $j++) {
        foreach ($employees as $key => $employee) {
          $attendance->employees()->attach($employee->id,['code_employee'=>$employee->code, 'hourIn'=>$workdays[$j], 'hourOut'=>$workdays[$j], 'forgiven'=> false, 'observation'=>"Falta"]);
        }
      }
      return response()->json(['saved' => true], 201);
    }

    public function massUpdate($id, Request $request){
      $data= $request->json()->all();
      $attendance= Attendence::find($id);
      $counter=0;
      for ($i=0; $i <count($data) ; $i++) {
        $timestamp=$data[$i]['fecha'];
        $code_employee=$data[$i]['code'];
        $datetime = explode(" ",$timestamp);
        $hour=$datetime[1];
        $day=$datetime[0];
        foreach ($attendance->employees as $employee) {
          $pivotId=$employee->pivot->id;
          $emploAtt=AttEmployee::find($pivotId);
          if ($employee->pivot->code_employee==$code_employee && $employee->pivot->hourIn==$day . " 00:00:00") {
            if (strtotime($hour)>=strtotime("12:00")) {
             $emploAtt->hourOut=$timestamp;
             if (strtotime(substr($emploAtt->hourIn,11))!=strtotime("00:00:00")) {
               $emploAtt->forgiven=true;
             }else {
               $emploAtt->forgiven=false;
             }
             $emploAtt->observation="Guardado";
             $emploAtt->save();

            }else {
               $emploAtt->hourIn=$timestamp;
               if (strtotime(substr($emploAtt->hourOut,11))!=strtotime("00:00:00")) {
                 $emploAtt->forgiven=true;
               }else {
                 $emploAtt->forgiven=false;
               }
               $emploAtt->observation="Guardado";
               $emploAtt->save();

            }

            $json[]=['code'=>$employee->pivot->code_employee, 'dateInBD'=>$employee->pivot->hourIn, 'dateOutBD'=>$employee->pivot->hourOut, 'day'=>$day . " 00:00:00"];
          }
        }
      }

      return response()->json(['updated' => true], 200);
    }

    public function show($idAtt, $code){
      $attendance=Attendence::find($idAtt);
      $employees=Employee::where('code', $code)->first();
      if (is_null($employees)) {
        return response()->json(['msj' => "Employee not found"], 404);
      }else {
        if (is_null($attendance)) {
          return response()->json(['msj' => "Attendance not found for this month"], 404);
        }else{
          $conditions=['attendance_id'=>$idAtt, 'employee_id'=>$employees->id];
          $attendances=AttEmployee::select('id','hourIn','hourOut','observation')->where($conditions)->get();
          foreach ($attendances as $attendance) {
            $att[]=[
                    'id'=>$attendance->id,
                    'date'=>substr($attendance->hourIn,0,10),
                    'hourIn'=>substr($attendance->hourIn,11),
                    'hourOut'=>substr($attendance->hourOut,11)
            ];
          }
        }
        return response()->json(['Attendances' => $att], 200);
      }
    }

    public function showAbsences($idAtt, $code){
      $attendance=Attendence::find($idAtt);
      $employees=Employee::where('code', $code)->first();
      if (is_null($employees)) {
        return response()->json(['msj' => "Employee not found"], 404);
      }else {
        if (is_null($attendance)) {
          return response()->json(['msj' => "Attendance not found for this month"], 404);
        }else{
          $conditions=['attendance_id'=>$idAtt, 'employee_id'=>$employees->id, 'forgiven'=>false];
          $attendances=AttEmployee::select('id','hourIn','hourOut','observation')->where($conditions)->get();
          foreach ($attendances as $attendance) {
            $att[]=[
                    'id'=>$attendance->id,
                    'date'=>substr($attendance->hourIn,0,10),
                    'hourIn'=>substr($attendance->hourIn,11),
                    'hourOut'=>substr($attendance->hourOut,11)
            ];
          }
        }
        return response()->json(['Attendances' => $att], 200);
      }
    }

    public function list(){
      $attendances=Attendence::select('id','name','type','divison')->get();
      return response()->json(['Attendances' => $attendances], 200);
    }

    public function update($id, Request $request){
        $data= $request->json()->all();
        $attendance=AttEmployee::find($id);
        if (is_null($attendance)) {
          return response()->json(['msj' => "Attendance not found"], 404);
        }else {
          $day=substr($attendance->hourIn,0,10);
          $attendance->hourIn=$day . " " . $data['hourIn'];
          $attendance->hourOut=$day . " " .$data['hourOut'];
          $attendance->forgiven=true;
          $attendance->observation=$data['observation'];
          $attendance->save();
          return response()->json(['updated' => true], 200);
        }
    }

    public function getHours($attId, $code){
      $employeeQ=Employee::where('code', $code)->first();
      $attendance=Attendence::find($attId);
      $conditions=['attendance_id'=>$attId, 'code_employee'=>$code];
      $attendances=AttEmployee::where($conditions)->get();
      $hours=0;
      foreach ($attendances as $att) {
          $hourIn=strtotime("00:00:00");
          $hourOut=strtotime("00:00:00");
          $hourIn=strtotime(substr($att->hourIn,11));
          $hourOut=strtotime(substr($att->hourOut,11));
          if ($hourIn<strtotime("08:00:00")) {
            $hourIn=strtotime("08:00:00");
          }
          if ($hourOut>strtotime("17:00:00")) {
            $hourIn=strtotime("17:00:00");
          }
          if ($hourIn==strtotime("00:00:00") && $hourOut>strtotime("12:00:00")) {
            $hour=strtotime("17:00:00")-$hourOut;
          }
          if ($hourOut==strtotime("00:00:00") && $hourIn>=strtotime("08:00:00")) {
            $hour=strtotime("12:00:00")-$hourIn;
          }
          if ($hourIn!=strtotime("00:00:00") && $hourOut!=strtotime("00:00:00")) {
            $hour=$hourOut-$hourIn-3600;
            $hour=$hourOut-$hourIn;
          }
          if ($hourOut==strtotime("00:00:00") && $hourIn==strtotime("08:00:00")) {
            $hourIn=strtotime("00:00:00");
            $hourOut=strtotime("00:00:00");
            $hour=$hourOut-$hourIn;
          }

          $hours+=$hour;
        $json[]=['id'=>$att->id,'hourIn'=>substr($att->hourIn,11), 'hourOut'=>substr($att->hourOut,11), 'hour'=>$hour, 'hours'=>$hours];
      }
      return response()->json(['hours' => round($hours/3600,2)], 200);
    }

    public function byPeriod($id)
    {
      $attendance=Attendence::find($id);
      foreach ($attendance->employees as $employee) {
        $hours=$this->getHours($attendance->id, $employee->code);
        $data=$hours->getData()->hours;
        $attEmployee[]=[
          'code'=>$employee->code,
          'name'=>$employee->people->fullname,
          'hour'=>$data
        ];
      }
      return response()->json(['employees' => $attEmployee], 200);
    }

}
