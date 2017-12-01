<?php

namespace App\Http\Controllers;
use App\Pay;
use App\Employee;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function generate(Request $request){
      $data= $request->json()->all();
      $TaxController='App\Http\Controllers\TaxController';
      $RentController='App\Http\Controllers\RentController';
      $conditions=['active'=>true, 'salarytype_id'=>$data['salarytype_id']];
      $employees=Employee::where($conditions)->get();
      $pay= new Pay;
      $pay->name=$data['name'];
      $pay->description=$data['description'];
      $pay->datePay=$data['datePay'];
      $pay->save();
      foreach ($employees as $employee) {
        $jsonisss=app($TaxController)->calculateISSS($employee->salary);
        $valisss=$jsonisss->getData()->isss;
        $jsonrent=app($RentController)->calculate($employee->salary);
        $valrent=$jsonrent->getData()->rent;
        $afpvalue=$employee->taxes->client;
        $pay->employees()->attach($employee->id, ['code_employee' => $employee->code, 'baseSalary'=>$employee->salary,
        'days'=>0, 'bond'=>0, 'ISSS'=>$valisss, 'rent'=>$valrent, 'pension'=>$employee->salary*$afpvalue, 'loans'=>0,
        'otherDiscounts'=>0,'otherIncomes'=>0,'total'=>$employee->salary-$valrent-$valisss-$employee->salary*$afpvalue]);
      }
      return response()->json(['saved' => true], 201);
    }

    public function calculate($id, Request $request){
      $data= $request->json()->all();
      $pay= Pay::find($id);
      foreach ($pay->employees as $employee) {
        $valloan=0;
        if ($employee->salarytype_id=$data['salarytype_id']) {
          $total=$employee->pivot->total;
          foreach ($employee->loans as $loan) {
            if ($loan->payed==false) {
               $valloan= $valloan+$loan->fee;
               app('App\Http\Controllers\LoanController')->discountLoan($loan->id);
            }
          }
          $pay->employees()->updateExistingPivot($employee->id,['loans'=>$valloan,'total'=>$total-$valloan]);
        }
      }
      return response()->json(['calculated' => true], 200);
    }

    public function otherDiscounts($idPay, $idEmployee, Request $request){
      $data= $request->json()->all();
      $pay=Pay::find($idPay);
        if (is_null($pay)) {
        return response()->json(['msj' => "Pay not found"], 404);
      }else{
        $emp=$pay->employees->find($idEmployee);
        if (is_null($emp)) {
          return response()->json(['msj' => "Employee not found"], 404);
        }else {
          $total=$emp->pivot->total;
          $pay->employees()->updateExistingPivot($emp->id, ['otherDiscounts'=>$data['value'],'total'=>$total-$data['value']]);
          return response()->json(['updated' => true], 200);
        }

      }
    }

    public function otherIncomes($idPay, $idEmployee, Request $request){
      $data= $request->json()->all();
      $pay=Pay::find($idPay);
        if (is_null($pay)) {
        return response()->json(['msj' => "Pay not found"], 404);
      }else{
        $emp=$pay->employees->find($idEmployee);
        if (is_null($emp)) {
          return response()->json(['msj' => "Employee not found"], 404);
        }else {
          $total=$emp->pivot->total;
          $pay->employees()->updateExistingPivot($emp->id, ['otherIncomes'=>$data['value'],'total'=>$total+$data['value']]);
          return response()->json(['updated' => true], 200);
        }

      }
    }

    public function list(){
      $pays=Pay::select('id','name','description','datePay')->get();
      return response()->json(['Pays' => $pays], 200);
    }

    public function listDetalied($id)
    {
      $pay=Pay::find($id);
      $jsonEmployees=array();
        if (is_null($pay)) {
        return response()->json(['msj' => "Pay not found"], 404);
      }else{
        foreach ($pay->employees as $employee) {
          $jsonEmployees[]=['code'=>$employee->pivot->code_employee,
                            'baseSalary'=>$employee->pivot->baseSalary,
                            'days'=>$employee->pivot->days,
                            'bond'=>$employee->pivot->bond,
                            'ISSS'=>$employee->pivot->ISSS,
                            'pension'=>$employee->pivot->pension,
                            'rent'=>$employee->pivot->rent,
                            'loans'=>$employee->pivot->loans,
                            'otherDiscounts'=>$employee->pivot->otherDiscounts,
                            'otherIncomes'=>$employee->pivot->otherIncomes,
                            'total'=>$employee->pivot->total,];
        }
        return response()->json($jsonEmployees, 200);
      }
    }

    public function show($id){
      $pay=Pay::find($id);
      if (is_null($pay)) {
        return response()->json(['msj' => "Pay not found"], 404);
      }else {
        $jsonEmployees=array();
        foreach ($pay->employees as $employee) {
          $jsonEmployees[]=[
            'id'=> $employee->pivot->employee_id,
            'code'=> $employee->pivot->code_employee,
            'name'=> $employee->people->fullname,
            'baseSalary'=> $employee->pivot->baseSalary,
            'days'=> $employee->pivot->days,
            'bond'=> $employee->pivot->bond,
            'ISSS'=> $employee->pivot->ISSS,
            'pension'=> $employee->pivot->pension,
            'rent'=> $employee->pivot->rent,
            'loans'=> $employee->pivot->loans,
            'otherDiscounts'=> $employee->pivot->otherDiscounts,
            'otherIncomes'=> $employee->pivot->otherIncomes,
            'total'=> $employee->pivot->total
          ];
        }
        return response()->json(['pay' => $jsonEmployees], 200);
      }
    }

}
