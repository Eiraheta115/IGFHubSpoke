<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;

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

  public function validateAccounttant($user){
      if ($user->email=="eiraheta@ues.com") {
        $date1 = date("Y-m-d");
        switch ($date1) {
          case '2017-11-29':
            $msj="Hay que calcular Aguinaldo";
            break;

          default:
            $msj=null;
            break;
        }
        return response()->json(['value'=> true, 'msj'=>$msj]);
      }else {
        $msj=null;
        return response()->json(['value'=> false, 'msj'=>$msj]);
      }
  }
}
