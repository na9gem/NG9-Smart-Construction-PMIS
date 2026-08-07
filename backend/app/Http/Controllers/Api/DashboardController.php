<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Document;
use App\Models\ProgressReport;
use App\Models\Inspection;
use App\Models\Media;

class DashboardController extends Controller
{
    /**
     * Dashboard Summary
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard Summary',
            'data' => [

                'projects' => Project::count(),

                'contracts' => Contract::count(),

                'documents' => Document::count(),

                'progress_reports' => ProgressReport::count(),

                'open_inspections' => Inspection::where('status', 'Open')->count(),

                'media' => Media::count(),

            ]
        ]);
    }
}
