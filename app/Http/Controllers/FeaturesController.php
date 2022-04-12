<?php

namespace App\Http\Controllers;

use App\Models\Features;
use Exception;
use Illuminate\Http\Request;

class FeaturesController extends Controller
{
    public function updateFeatures($id, Request $request)
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

    public function storeFeatures($projectid)
    {
        return $projectid;
    }
}
