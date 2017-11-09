<?php

namespace App\Http\Controllers;
use App\SalaryType;
use Illuminate\Http\Request;

class SalaryTypeController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $salary = new SalaryType;
    $salary->name=$data['name'];
    $salary->active=true;
    $salary->save();
    return response()->json(['saved' => true], 200);
  }

  // public function show($id){
  //   $salary= SalaryType::find($id);
  //   if (is_null($salary)) {
  //     return response()->json(['msj' => "SalaryTypes not found"], 404);
  //   }else{
  //     return $salary;
  //   }
  //
  // }

  public function list(){
    $salaries=SalaryType::select('id', 'name', 'active')->get();
    return response()->json($salaries);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $salary=SalaryType::find($id);
    if (is_null($salary)) {
      return response()->json(['msj' => "SalaryType not found"], 404);
    }else{
      $salary->name= $data['name'];
      $salary->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function updateState($id){
    $salary=SalaryType::find($id);
    if (is_null($salary)) {
      return response()->json(['msj' => "SalaryType not found"], 404);
    }else{
      if ($salary->active==true) {
          $salary->active=false;
      }else{
        $salary->active=true;
      }
      $salary->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function delete($id){
    $salary=SalaryType::find($id);
    if (is_null($salary)) {
      return response()->json(['msj' => "SalaryType not found"], 404);
    }else{
      $salary->delete();
      return response()->json(['msj' => "User deleted"], 202);
    }
  }
}
