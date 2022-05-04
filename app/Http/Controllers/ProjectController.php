<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

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
            ]);
            $attr['slug'] = str_slug($request->client);
            $newproject = Project::create($attr);
            $newproject->users()->attach($request->assignment_user);
            return response()->json([
                'message' => 'successfully',
                'project' => $newproject->with('users')->find($newproject->id),
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
            $project = Project::with('users', 'features')->where('slug', $slug)->first();
            return response()->json([
                'message' => 'successfully',
                'project' => $project,
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
            $project->update([
                'client' => $request->client,
                'status' => $request->status,
                'date' => Carbon::parse($request->date),
                'location' => $request->location,
                'phone_number' => $request->phone_number,
            ]);
            $project->users()->sync($request->assignment_user);
            return response()->json([
                'message' => 'successfully',
                'project' => $project->with('users', 'features')->find($project->id),
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
            $project->features()->delete();
            $project->users()->detach();
            $project->delete();
            return response()->json([
                'message' => 'successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getProjectsWithSearchKeyword(Request $request)
    {
        // $projects = Project::with('users')->latest();
        $projects = Project::with('users')->latest();

        // search keyword
        if ($s = $request->input('s')) {
            $projects
                ->where('client', 'like', '%' . $s . '%')
                ->orWhere('date', 'like', '%' . $s . '%')
                ->orWhere('status', 'like', '%' . $s . '%')
                ->orWhere('phone_number', 'like', '%' . $s . '%')
                ->orWhere('location', 'like', '%' . $s . '%')
                ->orWhere(function ($query) use ($s) {
                    $query->whereHas('users', function ($q) use ($s) {
                        $q->where('name', 'like', '%' . $s . '%');
                    });
                });
        }

        if ($category = $request->input('category')) {
            $projects
                ->where('status', 'like', '%' . $category . '%');
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
            return $project->users()->detach($user);
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

    // get all users
    public function getAllUsers()
    {
        try {
            $users = User::all();
            return response()->json([
                'message' => 'successfully',
                'users' => $users,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
