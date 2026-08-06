<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InspectionRequest;
use App\Models\Inspection;
use Illuminate\Http\JsonResponse;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $inspections = Inspection::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Inspection List',
            'data' => $inspections
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(InspectionRequest $request): JsonResponse
    {
        $inspection = Inspection::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Inspection Created Successfully',
            'data' => $inspection
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspection $inspection): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $inspection
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(
        InspectionRequest $request,
        Inspection $inspection
    ): JsonResponse {

        $inspection->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Inspection Updated Successfully',
            'data' => $inspection
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Inspection $inspection
    ): JsonResponse {

        $inspection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inspection Deleted Successfully'
        ]);
    }
}
