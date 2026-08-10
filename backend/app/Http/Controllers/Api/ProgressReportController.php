<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressReportRequest;
use App\Models\ProgressReport;
use Illuminate\Http\JsonResponse;

class ProgressReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $progressReports = ProgressReport::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Progress Report List',
            'data' => $progressReports
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(ProgressReportRequest $request): JsonResponse
    {
        $progressReport = ProgressReport::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Progress Report Created Successfully',
            'data' => $progressReport
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgressReport $progressReport): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $progressReport
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(
        ProgressReportRequest $request,
        ProgressReport $progressReport
    ): JsonResponse {

        $progressReport->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Progress Report Updated Successfully',
            'data' => $progressReport
        ]);
    }

    /**
 * Approve the specified Progress Report.
 */
public function approve(
    ProgressReport $progressReport
): JsonResponse {

    if ($progressReport->status !== 'Submitted') {
        return response()->json([
            'success' => false,
            'message' => 'สามารถอนุมัติได้เฉพาะ Progress Report ที่มีสถานะ Submitted เท่านั้น'
        ], 422);
    }

    $progressReport->update([
        'status' => 'Approved',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Progress Report Approved Successfully',
        'data' => $progressReport->fresh(),
    ]);
}

    /**
     * Remove the specified resource.
     */
    public function destroy(
        ProgressReport $progressReport
    ): JsonResponse {

        $progressReport->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress Report Deleted Successfully'
        ]);
    }
}
