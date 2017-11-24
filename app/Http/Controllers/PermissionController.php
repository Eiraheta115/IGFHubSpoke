<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

class PermissionController extends Controller
{
  public function decodeToken(){
    $payload= JWTAuth::parseToken()->getPayload();
    $policies=$payload->get('userData.policies');
    if (empty($policies)) {
      return response()->json(['msj' => "Policies not found"], 404);
    }else{
        return response()->json(['Data'=> $policies], 200);
    }
  }

  public function validateCandidates(){
    $policies=$this->decodeToken();
    $array=$policies->getData()->Data;
    if (in_array("CT0001",$array)) {
      return response()->json(['value'=> true]);
    }else {
      return response()->json(['value'=> false]);
     }
    }
}
