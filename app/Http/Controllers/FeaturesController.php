<?php

namespace App\Http\Controllers;

use App\Models\Features;
use Exception;
use Illuminate\Http\Request;

class FeaturesController extends Controller
{
    public function updateFeature($id, Request $request)
    {
        try {
            $features = Features::findOrFail($id);
            $features->update($request->all());
            return response()->json(
                ['message' => 'successfully updated'],
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeFeature($projectid, Request $request)
    {
        try {
            $feature = Features::create([
                'feature' => $request->feature,
                'status' => 0,
                'project_id' => $projectid,
            ]);
            return response()->json([
                'message' => 'successfully created',
                'feature' => $feature
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteFeature($id)
    {
        try {
            $feature = Features::findOrFail($id);
            $feature->delete();
            return response()->json(
                ['message' => 'successfully deleted'],
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
