<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkPackageRequest;
use App\Models\WorkPackage;
use Illuminate\Http\JsonResponse;

class WorkPackageController extends Controller
{
    /**
     * Display a listing of Work Packages.
     */
    public function index(): JsonResponse
    {
        $workPackages = WorkPackage::with([
            'milestone',
            'activities',
        ])
            ->orderBy('milestone_id')
            ->orderBy('sequence_no')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Work Package List',
            'data' => $workPackages,
        ]);
    }

    /**
     * Store a newly created Work Package.
     */
    public function store(
        WorkPackageRequest $request
    ): JsonResponse {
        $workPackage = WorkPackage::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Work Package Created Successfully',
            'data' => $workPackage->load([
                'milestone',
                'activities',
            ]),
        ], 201);
    }

    /**
     * Display the specified Work Package.
     */
    public function show(
        WorkPackage $workPackage
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data' => $workPackage->load([
                'milestone',
                'activities',
            ]),
        ]);
    }

    /**
     * Update the specified Work Package.
     */
    public function update(
        WorkPackageRequest $request,
        WorkPackage $workPackage
    ): JsonResponse {
        $workPackage->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Work Package Updated Successfully',
            'data' => $workPackage->load([
                'milestone',
                'activities',
            ]),
        ]);
    }

    /**
     * Remove the specified Work Package.
     */
    public function destroy(
        WorkPackage $workPackage
    ): JsonResponse {
        $workPackage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work Package Deleted Successfully',
        ]);
    }
}
