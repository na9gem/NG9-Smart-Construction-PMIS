<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressPlanItemRequest;
use App\Models\ProgressPlan;
use App\Models\ProgressPlanItem;
use Illuminate\Http\JsonResponse;

class ProgressPlanItemController extends Controller
{
    /**
     * Display a listing of Progress Plan Items.
     */
    public function index(
        ProgressPlan $progressPlan
    ): JsonResponse {
        $items = $progressPlan->items()
            ->with('activity')
            ->orderBy('plan_date')
            ->orderBy('activity_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item List',
            'data' => $items,
        ]);
    }

    /**
     * Store a newly created Progress Plan Item.
     */
    public function store(
        ProgressPlanItemRequest $request,
        ProgressPlan $progressPlan
    ): JsonResponse {
        $data = $request->validated();

        $data['progress_plan_id'] = $progressPlan->id;

        $item = ProgressPlanItem::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item Created Successfully',
            'data' => $item->load('activity'),
        ], 201);
    }

    /**
     * Display the specified Progress Plan Item.
     */
    public function show(
        ProgressPlan $progressPlan,
        ProgressPlanItem $progressPlanItem
    ): JsonResponse {
        abort_unless(
            $progressPlanItem->progress_plan_id === $progressPlan->id,
            404
        );

        return response()->json([
            'success' => true,
            'data' => $progressPlanItem->load('activity'),
        ]);
    }

    /**
     * Update the specified Progress Plan Item.
     */
    public function update(
        ProgressPlanItemRequest $request,
        ProgressPlan $progressPlan,
        ProgressPlanItem $progressPlanItem
    ): JsonResponse {
        abort_unless(
            $progressPlanItem->progress_plan_id === $progressPlan->id,
            404
        );

        $progressPlanItem->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item Updated Successfully',
            'data' => $progressPlanItem->load('activity'),
        ]);
    }

    /**
     * Remove the specified Progress Plan Item.
     */
    public function destroy(
        ProgressPlan $progressPlan,
        ProgressPlanItem $progressPlanItem
    ): JsonResponse {
        abort_unless(
            $progressPlanItem->progress_plan_id === $progressPlan->id,
            404
        );

        $progressPlanItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item Deleted Successfully',
        ]);
    }
}
