<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeaturesController;
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
//
Route::get('/test', function () {
    return 'It works!';
});

// test many to many relationship
Route::get('/test/many-to-many', function () {
    // project to user
    foreach (Project::all() as $project) {
        $project->users()->sync([rand(1, 3), rand(1, 3), rand(1, 3)]);
    }
    $show = $project->with('users')->get();

    // $users = User::all();

    // attach user to project
    // $project->users()->sync(rand(1, 10));
    // $show = $project->with('users')->get();

    // $eachproject = Project::where('id', 1);
    // $show = $eachproject->with('users')->first();

    // attach project to user
    // $user = User::find(2);
    // $user->projects()->sync([1, 2, 3, 4, 5]);

    // user to project
    // $project = Project::find(1);
    // $show = $project->users()->with('projects')->get();

    // show project from user
    // $user = User::find(3);
    // $show = $user->with('projects')->find(3);


    return response()->json($show);
    // return response()->json(['msg' => 'work']);
});

// login page
Route::post('/login', [AuthController::class, 'loginAuth']);

// set pass after registration
Route::get('/set-pass/{token_initial_password}', [AuthController::class, 'formSetPass']);
Route::post('/set-pass', [AuthController::class, 'setPass']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // auth
    Route::post('register', [AuthController::class, 'registerAuth']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getUser']);
    Route::get('user/{username}', [AuthController::class, 'getUserByUsername']);

    // query search and filter
    Route::get('projects', [ProjectController::class, 'getProjectsWithSearchKeyword']);

    Route::post('projects/store', [ProjectController::class, 'store']);
    Route::get('projects/{slug}', [ProjectController::class, 'show']);
    Route::patch('projects/update/{project}', [ProjectController::class, 'update']);

    // update features
    Route::post('features/{id}', [FeaturesController::class, 'updateFeature']);
    Route::post('projects/{projectid}/features/store', [FeaturesController::class, 'storeFeature']);
    Route::delete('features/{id}/delete', [FeaturesController::class, 'deleteFeature']);
});
