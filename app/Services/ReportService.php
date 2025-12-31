<?php

namespace App\Services;

use App\Exceptions\ConflictException;
use App\Jobs\GenerateUsersReportJob;
use App\Models\Report;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Storage;

class ReportService
{
  public function index() {
    return Report::latest()->paginate(6);
  }
  public function generate(array $data): Report
  {

    try {
      $report = Report::create($data);

      GenerateUsersReportJob::dispatch(
        $report->id,
        $data['start_date'],
        $data['end_date']
      );

      return $report;
    } catch (UniqueConstraintViolationException $e) {
      throw new ConflictException("Ya existe un reporte con el título: {$data['title']}");
    }
  }

  public function download(Report $report)
  {
    $path = $report->report_link;

    if (!$path) {
      throw new \Exception('Reporte no disponible para descarga.');
    }

    return Storage::disk('public')->download($path);
  }
}
