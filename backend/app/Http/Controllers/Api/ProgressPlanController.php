<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressPlanRequest;
use App\Models\ProgressPlan;
use Illuminate\Http\JsonResponse;

class ProgressPlanController extends Controller
{
    /**
     * Display a listing of Progress Plans.
     */
    public function index(): JsonResponse
    {
        $plans = ProgressPlan::with('items')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan List',
            'data' => $plans,
        ]);
    }

    /**
     * Store a newly created Progress Plan.
     *
     * If the new plan is marked as baseline,
     * disable the existing baseline for the same contract.
     */
    public function store(
        ProgressPlanRequest $request
    ): JsonResponse {

        $data = $request->validated();

        if (($data['is_baseline'] ?? false) === true) {
            ProgressPlan::where('contract_id', $data['contract_id'])
                ->where('is_baseline', true)
                ->update([
                    'is_baseline' => false,
                ]);
        }

        $plan = ProgressPlan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Created Successfully',
            'data' => $plan->load('items'),
        ], 201);
    }

    /**
     * Display the specified Progress Plan.
     */
    public function show(
        ProgressPlan $progressPlan
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'data' => $progressPlan->load('items'),
        ]);
    }

    /**
     * Update the specified Progress Plan.
     *
     * If the plan becomes a baseline,
     * disable other baselines for the same contract.
     */
    public function update(
        ProgressPlanRequest $request,
        ProgressPlan $progressPlan
    ): JsonResponse {

        $data = $request->validated();

        if (($data['is_baseline'] ?? false) === true) {
            ProgressPlan::where('contract_id', $data['contract_id'])
                ->where('id', '!=', $progressPlan->id)
                ->where('is_baseline', true)
                ->update([
                    'is_baseline' => false,
                ]);
        }

        $progressPlan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Updated Successfully',
            'data' => $progressPlan->load('items'),
        ]);
    }

    /**
     * Remove the specified Progress Plan.
     */
    public function destroy(
        ProgressPlan $progressPlan
    ): JsonResponse {

        $progressPlan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Deleted Successfully',
        ]);
    }
}