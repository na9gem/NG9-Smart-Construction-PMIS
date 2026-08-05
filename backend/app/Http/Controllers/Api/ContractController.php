<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractRequest;
use App\Models\Contract;
use Illuminate\Http\JsonResponse;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
{
    $contracts = Contract::latest()->get();

    return response()->json([
        'success' => true,
        'message' => 'Contract List',
        'data' => $contracts
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request): JsonResponse
{
    $contract = Contract::create($request->validated());

    return response()->json([
        'success' => true,
        'message' => 'Contract Created Successfully',
        'data' => $contract
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract): JsonResponse
{
    return response()->json([
        'success' => true,
        'data' => $contract
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, Contract $contract): JsonResponse
{
    $contract->update($request->validated());

    return response()->json([
        'success' => true,
        'message' => 'Contract Updated Successfully',
        'data' => $contract
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract): JsonResponse
{
    $contract->delete();

    return response()->json([
        'success' => true,
        'message' => 'Contract Deleted Successfully'
    ]);
}

}
