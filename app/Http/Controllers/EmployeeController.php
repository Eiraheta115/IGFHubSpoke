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
      $emPeople= new People;
      if (is_null($candidate)) {
        return response()->json(['msj' => "Candidate not found"], 404);
      }else{

        $emPeople->fullname =$candidate->people->fullname;
        $emPeople->sex =$candidate->people->sex;
        $emPeople->telephone =$candidate->people->telephone;
        $emPeople->cellphone =$candidate->people->cellphone;
        $emPeople->email =$candidate->people->email;
        $emPeople->dui =$candidate->people->dui;
        $emPeople->nit =$candidate->people->nit;
        $emPeople->isss =$candidate->people->isss;
        $emPeople->birthday =$candidate->people->birthday;
        $emPeople->direction =$candidate->people->direction;
        $emPeople->civilstatus_id =$candidate->people->civilstatus_id;

        $employee= new Employee;
        $employee->code=$data['code'];
        $employee->active=true;
        $employee->retired=false;
        $employee->admition=$data['admition'];
        // $employee->retirement=$data['retirement'];
        $employee->bankaccount=$data['bankaccount'];
        $employee->timeIn=$data['timeIn'];
        $employee->timeOut=$data['timeOut'];
        $employee->pensionType_id=$data['pensionType_id'];
        // $employee->day31=false;
        $employee->job_id=$data['job_id'];
        $employee->salary=$data['salary'];
        $employee->salarytype_id=$data['salarytype_id'];

        $candidate->hired=true;
        $candidate->save();
        $candidate->people()->delete();
        $employee->save();
        $employee->people()->save($emPeople);
        return response()->json(['saved' => true], 201);
      }
    }
}
