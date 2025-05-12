<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FacturaController;
use App\Http\Controllers\SurveyController;

Route::get('/', [SurveyController::class, 'getAll'])->name('surveys.index');


Route::post('/encuestas/lanzar', [SurveyController::class, 'lanzar'])->name('encuestas.lanzar');
Route::post('/encuestas/save', [SurveyController::class, 'save']);
Route::post('/encuestas/pregunta/save', [SurveyController::class, 'registrarPregunta']);
Route::post('/encuestas/enviar', [SurveyController::class, 'enviarEncuesta']);
Route::get('/encuestas/resultados/{idEncuesta}', [SurveyController::class, 'verResultados']);
