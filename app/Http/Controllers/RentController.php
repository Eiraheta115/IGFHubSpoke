<?php

namespace App\Http\Controllers;
use App\Rent;
use Illuminate\Http\Request;

class RentController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $rent = new Rent;
    $rent->name=$data['name'];
    $rent->since=$data['since'];
    $rent->until=$data['until'];
    $rent->value=$data['value'];
    $rent->top=$data['top'];
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
    $rents=Rent::select('id', 'name', 'since', 'until', 'value', 'top', 'fee', 'active')->get();
    return response()->json($rents, 200);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $rent=Rent::find($id);
    if (is_null($rent)) {
      return response()->json(['msj' => "Rent section not found"], 404);
    }else{
      $rent->since=$data['name'];
      $rent->since=$data['since'];
      $rent->until=$data['until'];
      $rent->value=$data['value'];
      $rent->value=$data['top'];
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

  public function calculate($value){
    $rents=Rent::where('active', true)->get();
    switch ($value) {
      case $value>=$rents[0]->since && $value<=$rents[0]->until:
        $rent=0.00;
        break;
      case $value>=$rents[1]->since && $value<=$rents[1]->until:
        $rent=$rents[1]->fee+($value-$rents[1]->top)*$rents[1]->value;
        break;
      case $value>=$rents[2]->since && $value<=$rents[2]->until:
        $rent=$rents[2]->fee+($value-$rents[2]->top)*$rents[2]->value;
        break;
      case $value>=$rents[3]->since && $value<=$rents[3]->until:
        $rent=$rents[3]->fee+($value-$rents[3]->top)*$rents[3]->value;
        break;
    }

    return response()->json(['rent' => round($rent, 2)], 200);
  }

}
