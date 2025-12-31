<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'report_link' => $this->when(
        filled($this->report_link),
        $this->report_link
      ),
      'created_at' => $this->created_at?->format('Y-m-d H:i:s')
    ];
  }
}
