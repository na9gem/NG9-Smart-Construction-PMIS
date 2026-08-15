<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MilestoneRequest;
use App\Models\Milestone;
use Illuminate\Http\JsonResponse;

class MilestoneController extends Controller
{
    /**
     * Display a listing of Milestones.
     */
    public function index(): JsonResponse
    {
        $milestones = Milestone::with('contract')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Milestone List',
            'data' => $milestones,
        ]);
    }

    /**
     * Store a newly created Milestone.
     */
    public function store(
        MilestoneRequest $request
    ): JsonResponse {
        $milestone = Milestone::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Milestone Created Successfully',
            'data' => $milestone->load('contract'),
        ], 201);
    }

    /**
     * Display the specified Milestone.
     */
    public function show(
        Milestone $milestone
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data' => $milestone->load('contract'),
        ]);
    }

    /**
     * Update the specified Milestone.
     */
    public function update(
        MilestoneRequest $request,
        Milestone $milestone
    ): JsonResponse {
        $milestone->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Milestone Updated Successfully',
            'data' => $milestone->load('contract'),
        ]);
    }

    /**
     * Remove the specified Milestone.
     */
    public function destroy(
        Milestone $milestone
    ): JsonResponse {
        $milestone->delete();

        return response()->json([
            'success' => true,
            'message' => 'Milestone Deleted Successfully',
        ]);
    }
}
