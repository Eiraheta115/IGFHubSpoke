<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evaluation;
use App\Candidate;
class EvaluationController extends Controller
{
  public function create(Request $request){
    $data= $request->json()->all();
    $evaluation = new Evaluation;
    $evaluation->name=$data['name'];
    $evaluation->state=false;
    $evaluation->observation=$data['observation'];
    $evaluation->save();
    return response()->json(['saved' => true], 201);
  }

  public function list(){
    $evaluations=Evaluation::select('id', 'name', 'state', 'observation')->get();
    return response()->json($evaluations);
  }

  public function massAddingCandidates($id, Request $request){
    $data= $request->json()->all();
    $ids=$data['ids'];
    $evaluation=Evaluation::find($id);
    if (is_null($evaluation)) {
      return response()->json(['msj' => "Evaluation not found"], 404);
    }else{
      foreach ($ids as $id) {
        $candidate=Candidate::find($id);
        $evaluation->candidates()->attach($candidate->id);
        app('App\Http\Controllers\CandidateController')->updateForMassUpdate($id,2);
      }
      return response()->json(['saved' => true], 201);
    }
  }

  public function updateState($id){
    $evaluation=Evaluation::find($id);
    if (is_null($evaluation)) {
      return response()->json(['msj' => "Evaluation not found"], 404);
    }else{
      if ($evaluation->state==true) {
          $evaluation->state=false;
      }else{
        $evaluation->state=true;
      }
      $evaluation->save();
      return response()->json(['updated' => true], 200);
    }
  }

  public function qualify($id, $idCandidate, Request $request){
    $data= $request->json()->all();
    $evaluation=Evaluation::find($id);
      if (is_null($evaluation)) {
      return response()->json(['msj' => "Evaluation not found"], 404);
    }else{
      $candidate=Candidate::find($idCandidate);
      if (is_null($candidate)) {
      return response()->json(['msj' => "Candidate not found"], 404);
    }else{
      $evaluation->candidates()->updateExistingPivot($candidate->id, ['grade'=>$data['grade']]);
      return response()->json(['saved' => true], 201);
      }
    }
  }

}
