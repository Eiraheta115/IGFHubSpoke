<?php

namespace App\Http\Controllers;
use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $job = new Job;
    $job->name=$data['name'];
    $job->description=$data['description'];
    $job->save();
    return response()->json(['saved' => true], 201);
  }

  // public function show($id){
  //   $job= Job::find($id);
  //   if (is_null($job)) {
  //     return response()->json(['msj' => "Job not found"], 404);
  //   }else{
  //     return $job;
  //   }
  //
  // }

  public function list(){
    $job=Job::select('id', 'name', 'description')->get();
    return response()->json($job);
  }

  public function update($id, Request $request){
    $data= $request->json()->all();
    $job=Job::find($id);
    if (is_null($job)) {
      return response()->json(['msj' => "Job not found"], 404);
    }else{
      $job->name= $data['name'];
      $job->description=$data['description'];
      $job->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function delete($id){
    $job=Job::find($id);
    if (is_null($job)) {
      return response()->json(['msj' => "Job not found"], 404);
    }else{
      $job->delete();
      return response()->json(['msj' => "Job deleted"], 202);
    }
  }
}
