<?php

namespace App\Http\Controllers;
use App\Rent;
use Illuminate\Http\Request;

class RentController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $rent = new Rent;
    $rent->since=$data['since'];
    $rent->until=$data['until'];
    $rent->value=$data['value'];
    $rent->fee=$data['fee'];
    $rent->active=true;
    $rent->save();
    return response()->json(['saved' => true], 201);
  }

  // public function show($id){
  //   $rent= Rent::find($id);
  //   if (is_null($rent)) {
  //     return response()->json(['msj' => "Rent section not found"], 404);
  //   }else{
  //     return $rent;
  //   }
  //
  // }

  public function list(){
    $rents=Rent::select('id', 'since', 'until', 'value', 'fee', 'active')->get();
    return response()->json($rents, 200);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $rent=Rent::find($id);
    if (is_null($rent)) {
      return response()->json(['msj' => "Rent section not found"], 404);
    }else{
      $rent->since=$data['since'];
      $rent->until=$data['until'];
      $rent->value=$data['value'];
      $rent->fee=$data['fee'];
      $rent->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function updateState($id){
    $rent=Rent::find($id);
    if (is_null($rent)) {
      return response()->json(['msj' => "Rent section not found"], 404);
    }else{
      if ($rent->active==true) {
          $rent->active=false;
      }else{
        $rent->active=true;
      }
      $rent->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function delete($id){
    $rent=Rent::find($id);
    if (is_null($rent)) {
      return response()->json(['msj' => "Rent section not found"], 404);
    }else{
      $rent->delete();
      return response()->json(['msj' => "Rent section deleted"], 202);
    }
  }
}
