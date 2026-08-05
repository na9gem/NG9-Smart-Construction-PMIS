<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(): JsonResponse
{
    $documents = Document::latest()->get();

    return response()->json([
        'success' => true,
        'message' => 'Document List',
        'data' => $documents
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentRequest $request): JsonResponse
{
    // รับไฟล์จาก Request
    $file = $request->file('document_file');

    // บันทึกไฟล์ลง Storage
    $path = $file->store('documents', 'public');

    // บันทึกข้อมูลลงฐานข้อมูล
    $document = Document::create([

        'project_id'     => $request->project_id,
        'contract_id'    => $request->contract_id,
        'document_type'  => $request->document_type,
        'document_name'  => $request->document_name,
        'document_no'   => $request->document_no,
        'document_date' => $request->document_date,

        'file_name'      => $file->getClientOriginalName(),
        'file_path'      => $path,
        'file_size'      => $file->getSize(),
        'file_extension' => $file->getClientOriginalExtension(),
        'mime_type'      => $file->getMimeType(),

        'uploaded_at'    => now(),
        'status'         => 'Draft',

        'revision' => $request->revision ?? '00',
        'remark'         => $request->remark,
        'uploaded_by'    => $request->uploaded_by,
    ]);


    return response()->json([
        'success' => true,
        'message' => 'Document Uploaded Successfully',
        'data'    => $document
    ], 201);
}




    /**
     * Display the specified resource.
     */
    public function show(Document $document): JsonResponse
{
    return response()->json([
        'success' => true,
        'data' => $document
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentRequest $request, Document $document): JsonResponse
{
    $document->update($request->validated());

    return response()->json([
        'success' => true,
        'message' => 'Document Updated Successfully',
        'data' => $document
    ]);
}
/**
 * Download document file
 */
public function download(Document $document)
{
    // ตรวจสอบว่าไฟล์ยังมีอยู่หรือไม่
    if (!Storage::disk('public')->exists($document->file_path)) {

        return response()->json([
            'success' => false,
            'message' => 'File not found'
        ], 404);

    }

    // ดาวน์โหลดไฟล์
    return Storage::disk('public')->download(
        $document->file_path,
        $document->file_name
    );
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): JsonResponse
{
    $document->delete();

    return response()->json([
        'success' => true,
        'message' => 'Document Deleted Successfully'
    ]);
}

}
