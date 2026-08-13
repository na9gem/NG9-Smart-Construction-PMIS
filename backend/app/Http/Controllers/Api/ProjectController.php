<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * แสดงรายการโครงการทั้งหมด
     */
    public function index(): JsonResponse
    {
        $projects = Project::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Project List',
            'data' => $projects
        ]);
    }

    /**
     * เพิ่มโครงการ
     */
    public function store(ProjectRequest $request): JsonResponse
{
    $project = Project::create([
        ...$request->validated(),
        'created_by' => Auth::id(),
    ]);

        return response()->json([
            'success' => true,
            'message' => 'Project Created Successfully',
            'data' => $project
        ],201);
    }

    /**
     * แสดงรายละเอียดโครงการ
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * แก้ไขข้อมูลโครงการ
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project Updated Successfully',
            'data' => $project
        ]);
    }

    /**
     * ลบโครงการ
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project Deleted Successfully'
        ]);
    }
}