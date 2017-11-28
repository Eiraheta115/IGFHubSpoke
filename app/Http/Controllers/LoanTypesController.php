<?php

namespace App\Http\Controllers;
use App\LoanTypes;
use Illuminate\Http\Request;

class LoanTypesController extends Controller
{
    public function create(Request $request){
      $data= $request->json()->all();
      $loantype = new LoanTypes;
      $loantype->name=$data['name'];
      $loantype->save();
      return response()->json(['saved' => true], 200);
    }

    public function list(){
      $loantypes=LoanTypes::select('id', 'name')->get();
      return response()->json($loantypes);
    }

    public function update($id, Request $request){
      $data= $request->json()->all();
      $loantype=LoanTypes::find($id);
      if (is_null($loantype)) {
        return response()->json(['msj' => "LoanTypes not found"], 404);
      }else{
        $loantype->name= $data['name'];
        $loantype->save();
        return response()->json(['updated' => true], 200);
      }
    }

    public function delete($id){
      $loantype=LoanTypes::find($id);
      if (is_null($loantype)) {
        return response()->json(['msj' => "LoanTypes not found"], 404);
      }else{
        $loantype->delete();
        return response()->json(['msj' => "LoanTypes deleted"], 202);
      }
    }
}
