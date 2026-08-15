<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;

class ActivityController extends Controller
{
    /**
     * Display a listing of Activities.
     */
    public function index(): JsonResponse
    {
        $activities = Activity::with([
            'workPackage',
        ])
            ->orderBy('work_package_id')
            ->orderBy('activity_code')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Activity List',
            'data' => $activities,
        ]);
    }

    /**
     * Store a newly created Activity.
     */
    public function store(
        ActivityRequest $request
    ): JsonResponse {
        $activity = Activity::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Activity Created Successfully',
            'data' => $activity->load([
                'workPackage',
            ]),
        ], 201);
    }

    /**
     * Display the specified Activity.
     */
    public function show(
        Activity $activity
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data' => $activity->load([
                'workPackage',
            ]),
        ]);
    }

    /**
     * Update the specified Activity.
     */
    public function update(
        ActivityRequest $request,
        Activity $activity
    ): JsonResponse {
        $activity->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Activity Updated Successfully',
            'data' => $activity->load([
                'workPackage',
            ]),
        ]);
    }

    /**
     * Remove the specified Activity.
     */
    public function destroy(
        Activity $activity
    ): JsonResponse {
        $activity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activity Deleted Successfully',
        ]);
    }
}
