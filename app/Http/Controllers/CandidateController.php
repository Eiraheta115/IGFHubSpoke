<?php

namespace App\Http\Controllers;
use App\Candidate;
use App\People;
use App\CivilStatus;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $candidate = new Candidate;
    $people = new People;
    $candidate->state=0;
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
    // $PermissionController='App\Http\Controllers\PermissionController';
    // $json=app($PermissionController)->validateCandidates();
    // $value=$json->getData()->value;
    // if ($value==true) {
    //   $candidates=Candidate::where('hired', false)->get();
    //   $jsonCandidate=array();
    //   foreach ($candidates as $candidate) {
    //     $jsonCandidate[]=[
    //       'id'=> $candidate->id,
    //       'state'=> $candidate->state,
    //       'fullname'=> $candidate->people->fullname
    //     ];
    //   }
    //   return response()->json($jsonCandidate, 200);
    // }else {
    //   return response()->json(['msj' => "You are not authorized to manage candidates"], 401);
    // }

      $candidates=Candidate::where('hired', false)->get();
      $jsonCandidate=array();
      foreach ($candidates as $candidate) {
        $jsonCandidate[]=[
          'id'=> $candidate->id,
          'state'=> $candidate->state,
          'fullname'=> $candidate->people->fullname
        ];
      }
      return response()->json($jsonCandidate, 200);
}

  public function byEvaluations($id){
    $conv= (int)$id;
    $jsonCandidates=array();
    $candidates=Candidate::where('hired', false)->get();
    foreach ($candidates as $candidate) {
      foreach ($candidate->evaluations as $evaluation) {
          if ($evaluation->pivot->evaluation_id==$conv) {
            $jsonCandidates[]=[
              'id'=> $candidate->id,
              'state'=> $candidate->state,
              'fullname'=> $candidate->people->fullname,
              'grade'=> (double)$evaluation->pivot->grade
            ];
          }
      }

    }
    return response()->json($jsonCandidates, 200);
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

  public function updateForMassUpdate($id, $value){
    $candidate=Candidate::find($id);
    if (is_null($candidate)) {
      return response()->json(['msj' => "Candidate not found"], 404);
    }else{
      $candidate->state=$value;
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

  public function listClassified(){
    $jsonCandidateSubscribed=array();
    $jsonCandidateInProcess=array();
    $jsonCandidateFinalist=array();
    $jsonCandidateDiscarded=array();

    $candidates=Candidate::where('hired', false)->get();
      foreach ($candidates as $candidate) {
        $jsonCandidateEvaluations=array();
      foreach ($candidate->evaluations as $evaluation) {
          $jsonCandidateEvaluations[]=['name'=>$evaluation->name,
          'grade'=> (double)$evaluation->pivot->grade];
      }
      switch ($candidate->state) {
        case 0:
        $jsonCandidateSubscribed[]=[
        'id'=> $candidate->id,
        'state'=> $candidate->state,
        'fullname'=> $candidate->people->fullname,
        'Evaluation'=> $jsonCandidateEvaluations
      ];
          break;
        case $candidate->state=1 || $candidate->state=2:
        $jsonCandidateInProcess[]=[
        'id'=> $candidate->id,
        'state'=> $candidate->state,
        'fullname'=> $candidate->people->fullname,
        'Evaluation'=> $jsonCandidateEvaluations
      ];
          break;
        case 3:
        $jsonCandidateFinalist[]=[
        'id'=> $candidate->id,
        'state'=> $candidate->state,
        'fullname'=> $candidate->people->fullname,
        'Evaluation'=> $jsonCandidateEvaluations
      ];
          break;
        case 4:
        $jsonCandidateDiscarded[]=[
        'id'=> $candidate->id,
        'state'=> $candidate->state,
        'fullname'=> $candidate->people->fullname,
        'Evaluation'=> $jsonCandidateEvaluations
      ];
          break;
      }
      unset($jsonCandidateEvaluations);
    }
    $jsonCandidates[]=[
      'Subscribed'=> $jsonCandidateSubscribed,
      'InProcess'=> $jsonCandidateInProcess,
      'Finalist'=> $jsonCandidateFinalist,
      'Discarted'=> $jsonCandidateDiscarded
    ];
    return response()->json($jsonCandidates, 200);
  }

  public function show($id)
  {
    $candidate=Candidate::find($id);
    if ($candidate->hired==true) {
        return response()->json(['msj' => "Candidate is already hired"], 400);
    }else {
      return response()->json(['fullname' => $candidate->people->fullname,
                               'dui'=> $candidate->people->dui,
                               'nit'=> $candidate->people->nit,
                               'isss'=> $candidate->people->isss,
                               'telephone'=> $candidate->people->telephone,
                               'cellphone'=> $candidate->people->cellphone,
                               'sex'=> $candidate->people->sex,
                               'CivilStatus'=> CivilStatus::find($candidate->people->civilstatus_id)->name,
                               'direction'=> $candidate->people->direction,], 200);
    }
  }

}
