<?php

namespace App\Http\Controllers;
use App\Tax;
use App\TaxDetail;
use Illuminate\Http\Request;

class TaxController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $tax = new Tax;
    $tax->name=$data['name'];
    $tax->client=$data['client'];
    $tax->patron=$data['patron'];
    $tax->roof=$data['roof'];
    $tax->active=true;
    $tax->save();
    return response()->json(['saved' => true], 201);
  }

  // public function show($id){
  //   $tax= Rent::find($id);
  //   if (is_null($tax)) {
  //     return response()->json(['msj' => "Tax not found"], 404);
  //   }else{
  //     return $tax;
  //   }
  //
  // }

  public function list(){
    $tax=Tax::select('id', 'name', 'client', 'patron', 'roof', 'active')->get();
    return response()->json($tax, 200);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $tax=Tax::find($id);
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      $tax->name=$data['name'];
      $tax->client=$data['client'];
      $tax->patron=$data['patron'];
      $tax->roof=$data['roof'];
      $tax->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function updateState($id){
    $tax=Tax::find($id);
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      if ($tax->active==true) {
          $tax->active=false;
      }else{
        $tax->active=true;
      }
      $tax->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function delete($id){
    $tax=Tax::find($id);
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      $tax->delete();
      $taxdetail=TaxDetail::where('taxe_id', $id)->delete();
      return response()->json(['msj' => "Tax deleted"], 202);
    }
  }
  public function calculateISSS($value)
  {
    $isssValue=Tax::where('name','isss')->first();
    return response()->json(['isss' => round($isssValue->client*$value, 2)], 200);
  }
}
