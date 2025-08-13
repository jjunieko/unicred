<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CooperadoController;

Route::prefix('cooperados')->group(function () {
    Route::get('/', [CooperadoController::class,'index']);
    Route::get('{id}', [CooperadoController::class,'show']);
    Route::post('/', [CooperadoController::class,'store']);
    Route::put('{id}', [CooperadoController::class,'update']);
    Route::delete('{id}', [CooperadoController::class,'destroy']); // soft delete
});