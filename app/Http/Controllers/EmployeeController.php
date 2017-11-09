<?php

namespace App\Http\Controllers;
use App\Candidate;
use App\Employee;
use App\People;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function hire($id, Request $request){
      $data= $request->json()->all();
      $candidate=Candidate::find($id);
      $people=People::where('peopleable_id', $candidate->id);
      if (is_null($candidate)) {
        return response()->json(['msj' => "Candidate not found"], 404);
      }else{
        $candidate->hired=true;
        $people->peopleable_type='App\Employee';
        $candidate->save();
        $employee= new Employee;
        $employee->code=$data['code'];
        $employee->active=true;
        $employee->retired=false;
        $employee->admition=$data['admition'];
        $employee->retirement=$data['retirement'];
        $employee->bankaccount=$data['bankaccount'];
        $employee->day31=false;
        $employee->job_id=$data['job_id'];
        $employee->salary=$data['salary'];
        $employee->salarytype_id=$data['salarytype_id'];
        $employee->save();
        return response()->json(['saved' => true], 201);
      }
    }
}
