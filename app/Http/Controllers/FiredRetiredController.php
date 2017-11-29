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
            break;
          case 'quincenal':
            $salary=round($employee->salary*2, 2);
            break;
          case 'semanal':
            $$salary=round($employee->salary*4, 2);
            break;
          case 'diario':
            $salary=round($employee->salary*30, 2);
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
        $fired->save();
        return response()->json(['saved' => true], 200);
      }


    }
}
