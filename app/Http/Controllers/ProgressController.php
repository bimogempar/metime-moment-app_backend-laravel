<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function storeProgress($project_id, Request $request)
    {
        try {
            $progress = new Progress;
            $progress->description = $request->description;
            $progress->user_id = $request->user_id;
            $progress->project_id = $project_id;
            $progress->save();

            $project = Project::with('progress', 'users')->find($project_id);
            $project->progress = $project->progress->map(function ($item) {
                $item->name = $item->user->name;
            });
            return response()->json([
                'message' => 'Successfully create new progress',
                'project' => $project,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroyProgress($project_id, $progress)
    {
        try {
            $progress = Progress::findOrFail($progress);
            $progress->delete();
            $project = Project::with('progress')->findOrFail($project_id);
            return response()->json([
                'message' => 'Successfully delete progress',
                'project' => $project,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
