<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressPlanItemRequest;
use App\Models\ProgressPlanItem;
use Illuminate\Http\JsonResponse;

class ProgressPlanItemController extends Controller
{
    /**
     * Store a newly created Progress Plan Item.
     */
    public function store(
        ProgressPlanItemRequest $request
    ): JsonResponse {

        $item = ProgressPlanItem::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item Created Successfully',
            'data' => $item,
        ], 201);
    }

    /**
     * Display the specified Progress Plan Item.
     */
    public function show(
        ProgressPlanItem $progressPlanItem
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'data' => $progressPlanItem,
        ]);
    }

    /**
     * Update the specified Progress Plan Item.
     */
    public function update(
        ProgressPlanItemRequest $request,
        ProgressPlanItem $progressPlanItem
    ): JsonResponse {

        $progressPlanItem->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item Updated Successfully',
            'data' => $progressPlanItem,
        ]);
    }

    /**
     * Remove the specified Progress Plan Item.
     */
    public function destroy(
        ProgressPlanItem $progressPlanItem
    ): JsonResponse {

        $progressPlanItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress Plan Item Deleted Successfully',
        ]);
    }
}
