<?php

namespace App\Http\Controllers;

use App\Events\EventProject;
use App\Events\NotifUser;
use App\Models\Features;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Progress;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use Str;
use PDF;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('users')->get();
        return response()->json($projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $attr = $request->validate([
                'client' => 'required',
                'date' => 'required',
                'time' => 'required',
                'location' => 'required',
                'status' => 'required',
                'phone_number' => 'required',
                'package_id' => 'required',
            ]);

            $attr['folder_gdrive'] = $request->client;
            $attr['slug'] = Str::random(10);

            // upload thumbnail project
            if ($request->hasFile('thumbnail_img')) {
                $request->validate([
                    'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $img = $request->file('thumbnail_img');
                $img_name = $attr['slug'] . '.' . $img->getClientOriginalExtension();
                $img->storeAs('public/thumbnail_img', $img_name);
                $attr['thumbnail_img'] = $img_name;
            }

            $newproject = Project::create($attr);

            $decodeAssignmentUser = json_decode($request->assignment_user);
            $newproject->users()->attach($decodeAssignmentUser);

            if (app()->environment('production')) {
                // GDRIVE API
                // create dir
                Storage::disk('google')->makeDirectory($request->client);
                $dir = "No files found";

                // upload into sub folder
                if ($request->hasFile('img')) {
                    $dir = '/';
                    $recursive = false; // Get subdirectories also?
                    $files = Storage::disk('google')->files($dir);
                    $contents = collect(Storage::disk('google')->listContents($dir, $recursive));
                    $dir = $contents->where('type', '=', 'dir')
                        ->where('filename', '=', $request->client)
                        ->first(); // There could be duplicate directory names!

                    // if (!$dir) {
                    //     return 'Directory does not exist!';
                    // }
                    Storage::disk('google')->put($dir['path'] . '/' . $request->file('img')->getClientOriginalName(), file_get_contents($request->file('img')));
                }
            }

            // chooee package
            $package = Package::find($request->package_id)->with('package_list')->first();
            $packageList = $package->package_list;
            foreach ($packageList as $value) {
                $arr[] = $value->name;
            }
            // insert into features
            foreach ($arr as $value) {
                $features = Features::create([
                    'project_id' => $newproject->id,
                    'feature' => $value,
                    'status' => 0,
                ]);
            }

            $newproject = $newproject->with('users', 'features', 'progress', 'package.package_list')->find($newproject->id);
            $users = User::find($decodeAssignmentUser);

            if (app()->environment('production')) {
                // mail to user for assigned project
                foreach ($users as $user) {
                    Mail::send('emails.new-project', ['user' => $user, 'newproject' => $newproject], function ($m) use ($user) {
                        $m->from('admin@metimemoment.com', 'Metime Moment');
                        $m->to($user->email)->subject('New Project Metime Moment');
                    });
                }
            }

            // make event project
            event(new EventProject(
                [
                    'message' => 'New Project Added',
                    'new_project' => $newproject->with('users', 'features', 'progress', 'package.package_list')->find($newproject->id),
                ]
            ));

            // make event for user
            foreach ($users as $user) {
                // insert event to db
                $notif = Notification::create([
                    'user_id' => $user->id,
                    'type' => 'new-project',
                    'message' => "New project assigned to you, the project is " . $newproject->client . " created by " . Auth()->user()->name,
                ]);

                // make new event for notif user
                event(new NotifUser($notif));
            }

            return response()->json([
                'message' => 'successfully',
                'project' => $newproject,
                'gdrive_path' => $dir ?? '',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $project = Project::with('users', 'features', 'progress', 'package.package_list')->where('slug', $slug)->first();
            $project->progress = $project->progress->map(function ($item) {
                $item->name = $item->user->name;
            });

            // show list files in folder gdrive
            $folder = $project->folder_gdrive;

            // Get root directory contents...
            $contents = collect(Storage::disk('google')->listContents('/', false));

            // Find the folder you are looking for...
            $dir = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $folder)
                ->first(); // There could be duplicate directory names!

            if (!$dir) {
                $files = "No such folder";
            } else {
                // Get the files inside the folder...
                $files = collect(Storage::disk('google')->listContents($dir['path'], false))
                    ->where('type', '=', 'file');

                $files = $files->map(function ($item) {
                    $item['path'] = Storage::disk('google')->url($item['path']);
                    return $item;
                });
            }

            return response()->json([
                'message' => 'successfully',
                'project' => $project,
                'dir' => $dir,
                'files_gdrive' => $files,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        try {
            $attr = $request->validate([
                'client' => 'required',
                'date' => 'required',
                'location' => 'required',
                'status' => 'required',
                'phone_number' => 'required',
            ]);

            // update thumbnail_img project
            if ($request->hasFile('thumbnail_img')) {
                Storage::disk('public')->delete('thumbnail_img/' . $project->thumbnail_img);
                $request->validate([
                    'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $img = $request->file('thumbnail_img');
                $img_name = $project->slug . '.' . $img->getClientOriginalExtension();
                $img->storeAs('public/thumbnail_img', $img_name);
                $attr['thumbnail_img'] = $img_name;
            } else {
                $attr['thumbnail_img'] = $project->thumbnail_img;
            }

            // update project
            $project->update($attr);

            $decodeAssignmentUser = json_decode($request->assignment_user);
            $project->users()->sync($decodeAssignmentUser);
            $project = $project->with('users', 'features')->find($project->id);
            return response()->json([
                'message' => 'successfully',
                'project' => $project,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->progress()->delete();
            $project->features()->delete();
            $project->users()->detach();
            if ($project->thumbnail_img) {
                Storage::disk('public')->delete('thumbnail_img/' . $project->thumbnail_img);
            }
            $project->delete();

            // Now find that directory and use its ID (path) to delete it
            if ($project->folder_gdrive) {
                $dir = '/';
                $recursive = true; // Get subdirectories also?
                $contents = collect(Storage::disk('google')->listContents($dir, $recursive));

                $directory = $contents
                    ->where('type', '=', 'dir')
                    ->where('filename', '=', $project->folder_gdrive)
                    ->first(); // there can be duplicate file names!

                Storage::disk('google')->deleteDirectory($directory['path']);
            }

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getProjectsWithSearchKeyword(Request $request)
    {
        $projects = Project::with('users', 'progress')->latest();

        // search keyword
        if ($s = $request->input('s')) {
            $projects
                ->where('client', 'ilike', '%' . $s . '%')
                ->orWhere('date', 'ilike', '%' . $s . '%')
                ->orWhere('status', 'ilike', '%' . $s . '%')
                ->orWhere('phone_number', 'ilike', '%' . $s . '%')
                ->orWhere('location', 'ilike', '%' . $s . '%')
                ->orWhere(function ($query) use ($s) {
                    $query->whereHas('users', function ($q) use ($s) {
                        $q->where('name', 'ilike', '%' . $s . '%');
                    });
                });
        }

        if ($category = $request->input('category')) {
            $projects
                ->where('status', 'ilike', '%' . $category . '%');
        }

        // sort by date
        if ($sort = $request->input('sort')) {
            $projects->orderBy('date', $sort);
        }

        // date range
        if ($start = $request->input('start') and $end = $request->input('end')) {
            $projects->whereBetween('date', [$start, $end])->get();
        }

        // paginate
        $perpage = 9;
        $page = $request->input('page', 1);
        $total = $projects->count();
        $result = $projects->offset(($page - 1) * $perpage)->limit($perpage)->get();

        return [
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perpage),
            'data' => $result,
        ];
    }

    // delete project_user
    public function deleteProjectUser($project, $user)
    {
        try {
            $project = Project::findOrFail($project);
            $project->users()->detach($user);
            return response()->json([
                'message' => 'successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    // add project_user
    public function addProjectUser($project, $user)
    {
        try {
            $project = Project::findOrFail($project);
            return $project->users()->attach($user);
            $project->users()->attach($user);
            return response()->json([
                'message' => 'successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    // get project to pdf
    public function getProjectPdf($slug)
    {
        $project = Project::with('users', 'features', 'package.package_list')->where('slug', $slug)->first();
        // $pdf = PDF::loadView('pdf/pdf-project', compact('project'));
        // return $pdf->stream($project->client);
        // return view('pdf.pdf-project', compact('project'));
        return response()->json([
            'message' => "Success",
            'project' => $project
        ]);
    }

    public function countByMonth()
    {
        $projects = Project::select('id', 'date')
            ->get()
            ->groupBy(function ($date) {
                //return Carbon::parse($date->date)->format('Y'); // grouping by years
                return Carbon::parse($date->date)->format('m'); // grouping by months
            });

        $projectCount = [];
        $projectArr = [];

        foreach ($projects as $key => $value) {
            $projectCount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($projectCount[$i])) {
                $projectArr[$i] = $projectCount[$i];
            } else {
                $projectArr[$i] = 0;
            }
        }

        return $projectArr;
    }
}
