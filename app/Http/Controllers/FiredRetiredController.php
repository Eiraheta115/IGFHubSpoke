<?php

namespace App\Http\Controllers;
use App\Employee;
use App\FiredRetired;
use Illuminate\Http\Request;

class FiredRetiredController extends Controller
{
    public function fired($id, Request $request){
      $TaxController='App\Http\Controllers\TaxController';
      $RentController='App\Http\Controllers\RentController';
      $data= $request->json()->all();
      $fired= new FiredRetired;
      $employee=Employee::find($id);
      if (is_null($employee)) {
        return response()->json(['msj' => "Employee not found"], 404);
      }else{
        if ($employee->active==true) {
          $employee->active=false;
          $fired->employee_id=$employee->id;
          $fired->code_employee=$employee->code;
          $fired->dateIn=$employee->admition;
          $fired->dateOut=$data['dateOut'];

          $date1 = $employee->admition;
          $date2 = $data['dateOut'];
          $diff = abs(strtotime($date2) - strtotime($date1));
          $years = floor($diff / (365*60*60*24));
          $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

          switch ($employee->salaryTypes->name) {
            case 'mensual':
              $salary=$employee->salary;
              $salaryMin=$salary/2;
              break;
            case 'quincenal':
              $salary=round($employee->salary*2, 2);
              $salaryMin=$salary;
              break;
            case 'semanal':
              $$salary=round($employee->salary*4, 2);
              $salaryMin=$salary*2;
              break;
            case 'diario':
              $salary=round($employee->salary*30, 2);
              $salaryMin=$salary*15;
              break;
           }

           switch ($salary) {
             case $salary<$salaryMin:
               $salary=$salaryMin;
               break;

             case $salary>1200.00:
               $salary=1200.00;
               break;
           }

          $totalYears=$salary*$years;
          $totalMonths=$salary*$months*30/365;
          $totalNoTax=$totalYears+$totalMonths;

          $jsonisss=app($TaxController)->calculateISSS($totalNoTax);
          $valisss=$jsonisss->getData()->isss;
          $jsonrent=app($RentController)->calculate($totalNoTax);
          $valrent=$jsonrent->getData()->rent;
          $afpvalue=$employee->taxes->client;
          $afp=$afpvalue*$totalNoTax;
          $total=$totalNoTax-$afp-$valrent-$valisss;

          $fired->isss=$valisss;
          $fired->pension=$afp;
          $fired->rent=$valrent;
          $fired->total=$total;
          $employee->save();
          $fired->save();
          return response()->json(['saved' => true], 200);
        }else{
          return response()->json(['msj' => "Employee not valid"], 400);
        }
      }
    }

    public function retired($id, Request $request){
      $TaxController='App\Http\Controllers\TaxController';
      $RentController='App\Http\Controllers\RentController';
      $data= $request->json()->all();
      $fired= new FiredRetired;
      $employee=Employee::find($id);
      if (is_null($employee)) {
        return response()->json(['msj' => "Employee not found"], 404);
      }else{
        if ($employee->active==true) {
          $employee->active=false;
          $fired->employee_id=$employee->id;
          $fired->code_employee=$employee->code;
          $fired->dateIn=$employee->admition;
          $fired->dateOut=$data['dateOut'];

          $date1 = $employee->admition;
          $date2 = $data['dateOut'];
          $diff = abs(strtotime($date2) - strtotime($date1));
          $years = floor($diff / (365*60*60*24));
          $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

          switch ($employee->salaryTypes->name) {
            case 'mensual':
              $salary=$employee->salary/2;
              $salaryMin=$salary/2;
              break;
            case 'quincenal':
              $salary=$employee->salary;
              $salaryMin=$salary;
              break;
            case 'semanal':
              $$salary=round($employee->salary*2, 2);
              $salaryMin=$salary*2;
              break;
            case 'diario':
              $salary=round($employee->salary*15, 2);
              $salaryMin=$salary*15;
              break;
           }

           switch ($salary) {
             case $salary<$salaryMin:
               $salary=$salaryMin;
               break;

             case $salary>600.00:
               $salary=600.00;
               break;
           }

          $totalYears=$salary*$years;
          $totalMonths=$salary*$months*30/365;
          $totalNoTax=$totalYears+$totalMonths;

          $jsonisss=app($TaxController)->calculateISSS($totalNoTax);
          $valisss=$jsonisss->getData()->isss;
          $jsonrent=app($RentController)->calculate($totalNoTax);
          $valrent=$jsonrent->getData()->rent;
          $afpvalue=$employee->taxes->client;
          $afp=$afpvalue*$totalNoTax;
          $total=$totalNoTax-$afp-$valrent-$valisss;

          $fired->isss=$valisss;
          $fired->pension=$afp;
          $fired->rent=$valrent;
          $fired->total=$total;
          $employee->save();
          $fired->save();
          return response()->json(['saved' => true], 200);
        }else{
          return response()->json(['msj' => "Employee not valid"], 400);
        }
      }
    }

}
