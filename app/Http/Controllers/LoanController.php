<?php

namespace App\Http\Controllers;
use App\Employee;
use App\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
  public function create($id, Request $request){
    $data= $request->json()->all();
    $employee=Employee::find($id);
    if (is_null($employee)) {
      return response()->json(['msj' => "Employee not found"], 404);
    }else{
      $loan = new Loan;
      $loan->loantypes_id=$data['loantypes_id'];
      $loan->employees_id=$employee->id;
      $loan->code_employee=$employee->code;
      $loan->code_loan=$data['code_loan'];
      $loan->deadline=$data['deadline'];
      $loan->value=$data['value'];
      $date1 = date("Y-m-d");
      $date2 = $data['deadline'];
      $diff = abs(strtotime($date2) - strtotime($date1))/(60*60*24);
      switch ($employee->salaryTypes->name) {
        case 'mensual':
          $loan->fee=$data['value']/($diff/30);
          break;
        case 'quincenal':
          $loan->fee=$data['value']/($diff/(30/2));
          break;
        case 'semanal':
          $loan->fee=$data['value']/($diff/(30/4));
          break;
        case 'diario':
          $loan->fee=$data['value']/($diff);
          break;
       }
      $loan->payed=false;
      $loan->save();
      return response()->json(['saved' => true], 200);
    }
  }
}
