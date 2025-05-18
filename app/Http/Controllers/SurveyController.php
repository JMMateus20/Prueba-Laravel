<?php

namespace App\Http\Controllers;

use App\Events\EncuestaLanzada;
use App\Events\ResultadosEncuesta;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroEncuestaRequest;
use App\Http\Requests\RegistroPreguntaRequest;
use App\Models\AnswerOption;
use App\Models\Question;
use App\Models\Survey;
use App\Models\UsersAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    public function getAll(){
        
        return view('encuestas', ['enc'=>$this->getAllAux()]);

    }

    public function lanzar(Request $req)
{
    $encuesta=Survey::with('questions.answers')->find($req->id);
    
    
    Log::info("Antes de lanzar evento");
    event(new EncuestaLanzada($encuesta));
    Log::info("Despues de lanzar evento");

    return response()->json([
        'title' => 'Encuesta enviada',
        'content' => 'La encuesta ha sido lanzada'
    ]);
}
    public function save(RegistroEncuestaRequest $req){
        try{
            DB::beginTransaction();
            $encuestaNew=Survey::create([
                'title'=>$req->titulo,
                'descripcion'=>$req->descripcion,
                'tipo'=>$req->tipo,
                'tiempo'=>$req->duracion_encuesta
            ]);
            $encuestaNew->load('questions.answers');
            DB::commit();
            return response()->json([
                'message'=>'Encuesta registrada con éxito',
                'encuesta'=>$encuestaNew
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    public function registrarPregunta(RegistroPreguntaRequest $req){
        
        try{
            DB::beginTransaction();
            $preguntaNew=Question::create([
                'question' => $req->enunciado,
                'has_correct_answer' => (isset($req->habilitarRespuestaCorrecta)) ? 1 : 0,
                'survey_id' => $req->idEncuesta
            ]);

            foreach ($req->opciones as $key => $o) {
                $answerNew=AnswerOption::create([
                    'answer' => $o['texto'],
                    'is_correct' => (isset($o['is_correct'])) ? 1 : 0,
                    'question_id' => $preguntaNew->id
                ]);
            }
            DB::commit();

            return response()->json([
                'message'=>'Pregunta registrada con éxito',
                'question' => $preguntaNew
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    public function enviarEncuesta(Request $req){
        try{
            DB::beginTransaction();
            foreach ($req->all() as $key => $respuesta) {
                $answerBD=AnswerOption::find($respuesta['idRespuesta']);
                $answerBD->usuarios_marcaron()->attach(3);
            }
            DB::commit();
            return response()->json([
                'message'=>'Encuesta registrada con éxito, gracias por tu participación'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    public function verResultados($idEncuesta){
        $encuesta=Survey::with('questions.answers.usuarios_marcaron')->find($idEncuesta);
        
        $respuesta=[];
        foreach ($encuesta->questions as $keyQ => $q) {
            $respuesta[$keyQ] =[
                
                'index' => $keyQ + 1,
                'pregunta' => $q->question,
                'respuestas' => []
                
            ];
            foreach ($q->answers as $keyA => $a) {
                $respuesta[$keyQ]['respuestas'][$keyA] =  [
                     
                    'index' => $keyA + 1,
                    'respuesta' => $a->answer,
                    'correcta' => $a->is_correct,
                    'usuarios' => []

                ];
                foreach ($a->usuarios_marcaron as $keyU => $u) {
                    $respuesta[$keyQ]['respuestas'][$keyA]['usuarios'][$keyU] = [
                        'nombre' => $u->name,
                        'nombre_usuario' => $u->username
                    ];
                }
            }
        }

        event(new ResultadosEncuesta($respuesta));
        return response()->json(['survey'=>$respuesta]);
    }


    

    public function getAllAux(){
        return Survey::with('questions.answers')->get();
    }


    
}
