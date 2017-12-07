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
      $loan->debt=$data['value'];
      $date1 = date("Y-m-d");
      $date2 = $data['deadline'];
      $diff = abs(strtotime($date2) - strtotime($date1))/(60*60*24);
      switch ($employee->salaryTypes->name) {
        case 'mensual':
          $fee=$data['value']/($diff/30);
          break;
        case 'quincenal':
          $fee=$data['value']/($diff/(30/2));
          break;
        case 'semanal':
          $fee=$data['value']/($diff/(30/4));
          break;
        case 'diario':
          $fee=$data['value']/($diff);
          break;
       }
       $loan->fee=$fee;
      $loan->payed=false;
      $loan->save();
      return response()->json(['saved' => true,'OriginDate'=>$date1, 'DeadLine'=>$date2, 'fee'=>round($fee,2)], 201);
    }
  }

  public function list(){
   $loans=Loan::select('id','code_loan','code_employee','deadline', 'value', 'fee', 'debt')->get();
   return response()->json($loans);
  }

  public function discountLoan($id){
    $loan=Loan::find($id);
    if (is_null($loan)) {
      return response()->json(['msj' => "Loan not found"], 404);
    }else {
      if ($loan->debt<$loan->fee) {
        $loan->debt=$loan->debt-$loan->debt;
        $loan->payed=true;
        $loan->save();
      }else {
        $loan->debt=$loan->debt-$loan->fee;
        $loan->save();
      }

      }
      return response()->json(['updated' => true], 200);
  }

}
