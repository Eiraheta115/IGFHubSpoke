<?php

namespace App\Http\Controllers;
use App\User;
use App\Policy;
use Illuminate\Http\Request;

class UserControl extends Controller{

  public function create(Request $request){
    $data= $request->json()->all();
    $user = new User;
    $user->fullname=$data['fullname'];
    $user->email=$data['email'];
    $user->password=bcrypt($data['password']);
    $user->group_id=$data['group_id'];
    $user->save();
    return $user;
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
    $users=User::select('fullname', 'email')->get();
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
      return response()->json(['msj' => "User updated",'user' => $user], 200);
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

}
