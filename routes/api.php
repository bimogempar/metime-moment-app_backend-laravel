<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\ProgressController;
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
Route::post('/test', function (Request $request) {
    return response()->json(['message' => 'It Works!']);
});

// Testing goodle drive filesystem
Route::post('/post-file-to-gdrive', function (Request $request) {
    // request
    $client = $request->client;
    $img = $request->file('img');

    // upload

    Storage::disk('google')->put($img->getClientOriginalName(), file_get_contents($img));
    $details = Storage::disk('google')->getmetaData(Storage::disk('google')->makeDirectory('/test'));
    return $details;
});

// get list directories from storage::disk
Route::get('/get-from-gdrive', function () {
    $directories = Storage::disk('google')->allDirectories();
    $metadata = Storage::disk('google')->getAdapter()->getMetaData('1vc1kw2bzQVNNUR8FGuk4EndTJ162ztYi');
    return $metadata['name'];
    $google = Storage::disk('google');
    $linkimg = $google->url('118j1FRoXuUyRDI5iu6Z_aKo1_xL1Nm4e');
    $img = '<img src="' . $linkimg . '" alt="">';
    $arrayImg = [];
    foreach ($directories as $directory) {
        $arrayImg[] = $google->url($directory);
    }
    return view('test/test', compact('arrayImg'));
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
Route::get('/set-pass/{token_initial_password}', [AuthController::class, 'getSetPass']);
Route::post('/set-pass', [AuthController::class, 'setInitPass']);
Route::post('/forgot-pass', [AuthController::class, 'forgotPass']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // auth
    Route::post('register', [AuthController::class, 'registerAuth']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getUser']);
    Route::get('user/{username}', [AuthController::class, 'getUserByUsername']);
    Route::get('user/{username}/settings', [AuthController::class, 'getUserSettings']);
    Route::patch('user/{username}/updateprofile', [AuthController::class, 'updateUserSetting']);

    // project
    Route::get('projects', [ProjectController::class, 'getProjectsWithSearchKeyword']);
    Route::post('projects/store', [ProjectController::class, 'store']);
    Route::get('projects/{slug}', [ProjectController::class, 'show']);
    Route::patch('projects/update/{project}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}/delete', [ProjectController::class, 'destroy']);

    // attach detach user to project
    Route::post('projects/{project}/add-user', [ProjectController::class, 'addProjectUser']);
    Route::delete('projects/{project}/user/{user}', [ProjectController::class, 'deleteProjectUser']);

    // fetch all users
    Route::get('users', [ProjectController::class, 'getAllUsers']);

    // features
    Route::patch('features/{id}', [FeaturesController::class, 'updateFeature']);
    Route::post('projects/{projectid}/features/store', [FeaturesController::class, 'storeFeature']);
    Route::delete('features/{id}/delete', [FeaturesController::class, 'deleteFeature']);

    // progress
    Route::post('projects/{project_id}/progress/store', [ProgressController::class, 'storeProgress']);
    Route::delete('projects/{project_id}/progress/{progress_id}', [ProgressController::class, 'destroyProgress']);
});
