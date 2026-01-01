<?php

namespace App\Jobs;

use App\Exports\UsersExport;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateUsersReportJob implements ShouldQueue
{
  use Queueable;

  /**
   * Create a new job instance.
   */
  public function __construct(
    public int $reportId,
    public string $startDate,
    public string $endDate,
  ) {}

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    try {
      $report = Report::find($this->reportId);
      $filename = "reports/{$report->id}_" . now()->timestamp . ".xlsx";

      Excel::store(
        new UsersExport($this->startDate, $this->endDate),
        $filename,
        'public'
      );

       // Verifica que el archivo existe
      if (!Storage::disk('public')->exists($filename)) {
        throw new \Exception("El archivo no se generó correctamente: {$filename}");
      }

      // Log para debug
      Log::info('Reporte generado exitosamente', [
        'reportId' => $this->reportId,
        'filename' => $filename,
        'path' => Storage::disk('public')->path($filename),
        'size' => Storage::disk('public')->size($filename),
      ]);

      $report->update([
        'report_link' => $filename,
      ]);
    } catch (\Exception $e) {
      Log::error('GenerateUsersReportJob failed', [
        'reportId' => $this->reportId,
        'message'  => $e->getMessage(),
      ]);

      throw $e;
    }
  }
}
