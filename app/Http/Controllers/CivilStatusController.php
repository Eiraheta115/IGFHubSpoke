<?php

namespace App\Http\Controllers;
use App\CivilStatus;
use Illuminate\Http\Request;

class CivilStatusController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $civilStatus = new CivilStatus;
    $civilStatus->name=$data['name'];
    $civilStatus->save();
    return response()->json(['saved' => true], 201);
  }

  // public function show($id){
  //   $civilStatus= CivilStatus::find($id);
  //   if (is_null($civilStatus)) {
  //     return response()->json(['msj' => "CivilStatus not found"], 404);
  //   }else{
  //     return $civilStatus;
  //   }
  //
  // }

  public function list(){
    $civilStatuses=CivilStatus::select('id', 'name')->get();
    return response()->json($civilStatuses);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $civilStatus=CivilStatus::find($id);
    if (is_null($civilStatus)) {
      return response()->json(['msj' => "CivilStatus not found"], 404);
    }else{
      $civilStatus->name= $data['name'];
      $civilStatus->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function delete($id){
    $civilStatus=CivilStatus::find($id);
    if (is_null($civilStatus)) {
      return response()->json(['msj' => "CivilStatus not found"], 404);
    }else{
      $civilStatus->delete();
      return response()->json(['msj' => "CivilStatus deleted"], 202);
    }
  }
}
