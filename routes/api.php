<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/hello', function () {
    return 'hello world';
});

Route::get('/test', function () {
    // project to user
    // $project = Project::find(4);
    // $users = User::all();

    // attach user to project
    // $project->users()->sync([2, 3, 4, 5, 6, 8]);

    // $eachproject = Project::where('id', 1);
    // $show = $eachproject->with('users')->first();

    // attach project to user
    $user = User::find(2);
    $user->projects()->sync([1, 2, 3, 4, 5]);

    // user to project
    // $project = Project::find(1);
    // $show = $project->users()->with('projects')->get();

    // show project from user
    $show = $user->projects()->get();

    return response()->json($show);
    // return response()->json(['msg' => 'work']);
});

// login page
Route::post('login', [AuthController::class, 'loginAuth']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // auth
    Route::post('register', [AuthController::class, 'registerAuth']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getUser']);

    // query search and filter
    Route::get('projects', [ProjectController::class, 'querySearch']);
});
