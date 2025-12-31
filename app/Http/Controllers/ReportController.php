<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
  public function __construct(
    protected ReportService $reportService
  ) {}

  public function index()
  {
    $reports = $this->reportService->index();
    return ReportResource::collection($reports);
  }

  public function generate(GenerateReportRequest $request)
  {
    $validated = $request->validated();
    $report = $this->reportService->generate($validated);

    return response()->json([
      'message' => 'Reporte encolado',
      'data' => new ReportResource($report),
    ], 201);
  }

  public function download(Report $report_id)
  {
    return $this->reportService->download($report_id);
  }
}
