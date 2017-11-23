<?php

namespace App\Http\Controllers;
use Hash;
use App\User;
use App\Policy;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use JWTFactory;
class UserControl extends Controller{

  public function create(Request $request){
    $data= $request->json()->all();
    $user = new User;
    $user->fullname=$data['fullname'];
    $user->email=$data['email'];
    $user->password=bcrypt($data['password']);
    $user->group_id=$data['group_id'];
    $user->save();
    $token= JWTAuth::fromUser($user);
    return response()->json(['saved' => true, 'token'=> compact('token')], 201);
  }

  public function show($id){
    $user= User::find($id);
    if (is_null($user)) {
      return response()->json(['msj' => "User not found"], 404);
    }else{
      return $user;
    }

  }

  public function list(){
    $users=User::select('id','fullname', 'email')->get();
    return response()->json($users);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $user=User::find($id);
    if (is_null($user)) {
      return response()->json(['msj' => "User not found"], 404);
    }else{
      $user->fullname= $data['fullname'];
      $user->email=$data['email'];
      $user->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function delete($id){
    $user=User::find($id);
    if (is_null($user)) {
      return response()->json(['msj' => "User not found"], 404);
    }else{
      $user->delete();
      return response()->json(['msj' => "User deleted"], 202);
    }
  }

  public function setPolicies($id, Request $request){
  $data= $request->json()->all();
  $user=User::find($id);
  $policy= new Policy;
  $policy->name=$data['name'];
  $policy->code=$data['code'];
  $user->policies()->save($policy);
  return $user->policies;
  }

  public function login(Request $request){
    $data= $request->json()->all();
    $credentials = Input::only('email', 'password');
    $jsonPolicies=array();
    $user=User::where('email',$data['email'])->first();
    if (is_null($user)) {
      return response()->json(['msj' => "User not found"], 404);
    }else{
        if ( ! $token = JWTAuth::attempt($credentials)) {
          return response()->json([false, HttpResponse::HTTP_UNAUTHORIZED], 400);
        }
        foreach ($user->policies as $policie) {
          $jsonPolicies[]=($policie->code);
        }
        $payloadable=$userLogged=[
          'fullname'=> $user->fullname,
          'email'=> $user->email,
          'policies'=>$jsonPolicies
        ];

        $tokenUserData =  JWTAuth::encode( JWTFactory::make( $payloadable ) );
        return response()->json(['token'=> $tokenUserData->get(),'user' => $userLogged], 201);

    }
  }

  public function decodeToken(Request $request)
  {
    $data= $request->json()->all();
    $token=$data['token'];
    $payload= JWTAuth::parseToken()->getPayload($token);
    return response()->json(['Payload'=> $payload, 'status'=> 'c mamo :v'], 200);
  }

}
