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

// Testing upload file
Route::post('/upload-file', function (Request $request) {
    $img = $request->file('img');
    return $img->storeAs('/test', 'test.png');
});

// Testing DOMPDF
Route::get('/test-dompdf', function (Request $request) {
    return view('test/test-dompdf');
});
Route::get('/get-dompdf', function (Request $request) {
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML('<h1>Test</h1>');
    return $pdf->stream();
});

// Testing google drive filesystem
Route::post('/post-file-to-gdrive', function (Request $request) {
    // request
    $client = $request->client;
    $img = $request->file('img');

    Project::create([
        'client' => $client,
        'slug' => str_random(10),
        'date' => '2020-01-17',
        'time' => '17:39:44',
        'location' => 'Lorem, ipsum dolor.',
        'status' => 0,
        'phone_number' => '+2348090872323',
        'folder_gdrive' => $client,
    ]);

    // create dir
    Storage::disk('google')->makeDirectory($client);

    // upload into sub folder
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $files = Storage::disk('google')->files($dir);
    $contents = collect(Storage::disk('google')->listContents($dir, $recursive));

    $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', $client)
        ->first(); // There could be duplicate directory names!

    if (!$dir) {
        return 'Directory does not exist!';
    }

    Storage::disk('google')->put($dir['path'] . '/' . $img->getClientOriginalName(), file_get_contents($img));
    return $dir;
});
Route::get('/get-from-gdrive', function () {
    // The human readable folder name to get the contents of...
    // For simplicity, this folder is assumed to exist in the root directory.
    $folder = 'testing client';

    // Get root directory contents...
    $contents = collect(Storage::disk('google')->listContents('/', false));

    // Find the folder you are looking for...
    $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', $folder)
        ->first(); // There could be duplicate directory names!

    if (!$dir) {
        return 'No such folder!';
    }

    // Get the files inside the folder...
    $files = collect(Storage::disk('google')->listContents($dir['path'], false))
        ->where('type', '=', 'file');

    // return $files;
    return $files->mapWithKeys(function ($file) {
        $filename = $file['filename'] . '.' . $file['extension'];

        // without slicing path
        $path = $file['path'];

        // slicing path
        // $path = explode('/', $file['path']);
        // $path = $path[0];

        // Use the path to download each file via a generated link..
        // Storage::disk('google')->get($file['path']);
        return [$filename => Storage::disk('google')->url($path)];
        // return [$filename => $path];
    });
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
    Route::post('user/{username}/updateprofile', [AuthController::class, 'updateUserSetting']);

    // project
    Route::get('projects', [ProjectController::class, 'getProjectsWithSearchKeyword']);
    Route::post('projects/store', [ProjectController::class, 'store']);
    Route::get('projects/{slug}', [ProjectController::class, 'show']);
    Route::patch('projects/update/{project}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}/delete', [ProjectController::class, 'destroy']);
    Route::get('projects/{slug}/get-project-pdf', [ProjectController::class, 'getProjectPdf']);

    // attach detach user to project
    Route::post('projects/{project}/add-user', [ProjectController::class, 'addProjectUser']);
    Route::delete('projects/{project}/user/{user}', [ProjectController::class, 'deleteProjectUser']);

    // fetch all users
    Route::get('users', [AuthController::class, 'getAllUsers']);

    // features
    Route::patch('features/{id}', [FeaturesController::class, 'updateFeature']);
    Route::post('projects/{projectid}/features/store', [FeaturesController::class, 'storeFeature']);
    Route::delete('features/{id}/delete', [FeaturesController::class, 'deleteFeature']);

    // progress
    Route::post('projects/{project_id}/progress/store', [ProgressController::class, 'storeProgress']);
    Route::delete('projects/{project_id}/progress/{progress_id}', [ProgressController::class, 'destroyProgress']);
});
