<?php

namespace App\Http\Controllers;
use App\TaxDetail;
use App\Tax;
use Illuminate\Http\Request;

class TaxDetailController extends Controller
{
  public function create($id, Request $request){
    $data= $request->json()->all();
    $tax = Tax::find($id);
    $taxdetail= new TaxDetail;
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      //$taxdetail->name=$data['name'];
      $taxdetail->value=$data['value'];
      $tax->taxdetails()->save($taxdetail);
      return response()->json(['saved' => true], 201);
    }
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

  public function list($id){
    $tax = Tax::find($id);
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      $taxdetail=TaxDetail::select('value')->where('taxe_id', $id)->get();
      return response()->json($taxdetail, 200);
    }
  }

  public function update($id, $idDetail, Request $request){
    $data= $request->json()->all();
    $tax=Tax::find($id);
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      $taxdetail=TaxDetail::find($idDetail);
      if (is_null($taxdetail)) {
        return response()->json(['msj' => "TaxDetail not found"], 404);
      }else {
        $taxdetail->value=$data['value'];
        $tax->taxdetails()->save($taxdetail);
        return response()->json(['updated' => true], 200);
      }
    }
  }

  public function delete($id, $idDetail, Request $request){
    $data= $request->json()->all();
    $tax=Tax::find($id);
    if (is_null($tax)) {
      return response()->json(['msj' => "Tax not found"], 404);
    }else{
      $taxdetail=TaxDetail::find($idDetail);
      if (is_null($taxdetail)) {
        return response()->json(['msj' => "TaxDetail not found"], 404);
      }else {
        $taxdetail->delete();
        return response()->json(['msj' => "TaxDetail deleted"], 202);
      }
    }
   }
}
