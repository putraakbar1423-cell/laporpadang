<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'icon' => $this->icon ?? 'category',
            'color' => $this->color ?? '#2E7D32',
            'report_count' => (int) ($this->reports_count ?? $this->reports()->count()),
        ];
    }
}
