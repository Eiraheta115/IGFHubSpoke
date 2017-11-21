<?php

namespace App\Http\Controllers;
use App\Group;
use App\Policy;
use Illuminate\Http\Request;

class GroupController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $group = new Group;
    $group->name=$data['name'];
    $group->save();
    return response()->json(['saved' => true], 201);
  }

  public function show($id){
    $group=Group::find($id)->policies;
    if (is_null($group)) {
      return response()->json(['msj' => "Group not found"], 404);
    }else{
      return new $group;
    }

  }

  public function list(){
    $groups=Group::select('id', 'name')->get();
    return response()->json($groups);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $group=Group::find($id);
    if (is_null($group)) {
      return response()->json(['msj' => "Group not found"], 404);
    }else{
      $group->name= $data['name'];
      $group->save();
      return response()->json(['msj' => "Group updated",'grup' => $group], 200);
    }
  }

  public function delete($id){
    $group=Group::find($id);
    if (is_null($group)) {
      return response()->json(['msj' => "Group not found"], 404);
    }else{
      $group->delete();
      return response()->json(['msj' => "Group deleted"], 202);
    }
  }

  public function addPolicies($id, Request $request){
    $data= $request->json()->all();
    $ids=$data['ids'];
    $group= group::find($id);
    if (is_null($group)) {
      return response()->json(['msj'=> "Group not found"], 404);
    }else {
      foreach ($ids as $id) {
        $policy=Policy::find($id);
        $group->policies()->attach($policy->id);
      }
      return response()->json(['saved' => true], 201);
    }
  }

  public function removePolicies($id, Request $request){
    $data= $request->json()->all();
    $ids=$data['ids'];
    $group= group::find($id);
    if (is_null($group)) {
      return response()->json(['msj'=> "Group not found"], 404);
    }else {
      $group->policies()->detach($ids);
      return response()->json(['deleted' => true], 202);
    }
  }
}
