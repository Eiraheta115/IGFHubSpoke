<?php

namespace App\Http\Controllers;
use App\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $policy = new Policy;
    $policy->name=$data['name'];
    $policy->code=$data['code'];
    $policy->save();
    return $policy;
  }

  public function show($id){
    $policy=Policy::find($id)->users;
    if (is_null($policy)) {
      return response()->json(['msj' => "policy not found"], 404);
    }else{
      //return new PolicyResource($policy);
      return $policy;
    }

  }

  public function list(){
    $policy=Policy::select('id','name', 'code')->get();
    return response()->json($policy);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $policy=Policy::find($id);
    if (is_null($policy)) {
      return response()->json(['msj' => "Policy not found"], 404);
    }else{
      $policy->name= $data['name'];
      $policy->code=$data['code'];
      $policy->save();
      return response()->json(['msj' => "Policy updated",'user' => $policy], 200);
    }
  }

  public function delete($id){
    $policy=Policy::find($id);
    if (is_null($policy)) {
      return response()->json(['msj' => "Policy not found"], 404);
    }else{
      $policy->delete();
      return response()->json(['msj' => "Policy deleted"], 202);
    }
  }
}
