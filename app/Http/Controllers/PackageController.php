<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Exception;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function getPackages()
    {
        try {
            $packages = Package::with('package_list')->latest();

            // pagination packages
            $perPage = request()->has('page') ? request()->page : 10;
            $packages = $packages->paginate($perPage);

            return response()->json([
                'message' => 'success', 'packages' => $packages
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showPackage($id)
    {
        try {
            $package = Package::with('package_list')->findOrFail($id);
            return response()->json([
                'message' => 'success',
                'package' => $package->with('package_list')->get()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storePackage(Request $request)
    {
        try {
            $package = Package::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            $package->package_list()->createMany($request->package_list);

            return response()->json([
                'message' => 'success',
                'package' => $package->with('package_list')->latest()->get()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePackage(Request $request, $id)
    {
        try {
            $findPackage = Package::with('package_list')->find($id);
            $findPackage->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            $findPackage->package_list()->delete();
            $findPackage->package_list()->createMany($request->package_list);
            $package = $findPackage->with('package_list')->find($id);

            return response()->json([
                'message' => 'success',
                'package' => $package
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyPackage($id)
    {
        try {
            $package = Package::find($id);
            $package->package_list()->delete();
            $package->delete();

            return response()->json([
                'message' => 'Deleted Successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
