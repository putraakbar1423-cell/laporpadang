<?php

namespace App\Http\Resources;

use App\Models\Report;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Whether to include per-user report statistics in the payload.
     * Off by default (e.g. inside auth responses); on for the profile endpoint.
     */
    public bool $withStatistics = false;

    public static function withStatistics(): self
    {
        $resource = new self(request()->user());
        $resource->withStatistics = true;

        return $resource;
    }

    public function toArray($request): array
    {
        $data = [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'role' => $this->role ?? 'user',
            'created_at' => $this->created_at?->toIso8601String(),
        ];

        if ($this->withStatistics) {
            $data['statistics'] = [
                'total_reports' => (int) $this->reports()->count(),
                'in_progress' => (int) $this->reports()
                    ->whereIn('status', [Report::STATUS_PENDING, Report::STATUS_PROCESS])
                    ->count(),
                'completed' => (int) $this->reports()
                    ->where('status', Report::STATUS_DONE)
                    ->count(),
                'rejected' => (int) $this->reports()
                    ->where('status', Report::STATUS_REJECTED)
                    ->count(),
            ];
        }

        return $data;
    }
}
