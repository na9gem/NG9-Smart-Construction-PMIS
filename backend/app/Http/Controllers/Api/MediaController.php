<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaRequest;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of media.
     */
    public function index(): JsonResponse
    {
        $media = Media::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Media List',
            'data' => $media,
        ]);
    }

    /**
     * Store uploaded media.
     */
    public function store(MediaRequest $request): JsonResponse
    {
        $file = $request->file('media_file');

        $path = $file->store('media', 'public');

        $media = Media::create([
            'project_id' => $request->project_id,
            'contract_id' => $request->contract_id,
            'progress_report_id' => $request->progress_report_id,
            'inspection_id' => $request->inspection_id,
            'media_type' => $request->media_type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media Uploaded Successfully',
            'data' => $media,
        ], 201);
    }

    /**
     * Display the specified media.
     */
    public function show(Media $media): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $media,
        ]);
    }

    /**
     * Delete media.
     */
    public function destroy(Media $media): JsonResponse
    {
        Storage::disk('public')->delete($media->file_path);

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media Deleted Successfully',
        ]);
    }
}
