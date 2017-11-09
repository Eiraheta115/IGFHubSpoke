<?php

namespace App\Http\Controllers;
use App\Candidate;
use App\People;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $candidate = new Candidate;
    $people = new People;
    $candidate->state=$data['state'];
    $candidate->hired=false;
    $candidate->observation=$data['observation'];
    $candidate->save();
    $people->fullname =$data['fullname'];
    $people->sex =$data['sex'];
    $people->telephone =$data['telephone'];
    $people->cellphone =$data['cellphone'];
    $people->email =$data['email'];
    $people->dui =$data['dui'];
    $people->nit =$data['nit'];
    $people->isss =$data['isss'];
    $people->birthday =$data['birthday'];
    $people->direction =$data['direction'];
    $people->civilstatus_id =$data['civilstatus_id'];
    $candidate->people()->save($people);
    return response()->json(['saved' => true], 201);
  }

  public function list(){
    $candidates=Candidate::all();
    foreach ($candidates as $candidate) {
      $jsonCandidate[]=[
        'id'=> $candidate->id,
        'state'=> $candidate->state,
        'fullname'=> $candidate->people->fullname
      ];
    }
    return response()->json($jsonCandidate);
  }

  public function update(){
    $data= $request->json()->all();
    $candidate=Candidate::find($id);
    if (is_null($candidate)) {
      return response()->json(['msj' => "Candidate not found"], 404);
    }else{
      $candidate->state=$data['state'];
      $candidate->observation=$data['observation'];
      $candidate->save();
    }
  }

  public function updateState($id, Request $request){
    $data= $request->json()->all();
    $candidates=Candidate::find($id);
    if (is_null($candidates)) {
      return response()->json(['msj' => "Candidate not found"], 404);
    }else{
      $candidates->state=$data['change'];
      $candidates->save();
      return response()->json(['updated' => true], 200);
    }
  }

}
