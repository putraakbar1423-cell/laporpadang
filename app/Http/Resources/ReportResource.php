<?php

namespace App\Http\Resources;

use App\Models\Report;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Map the internal report (DB schema) into the shape expected by the
     * Flutter `ReportModel`. The DB now stores `images` as a JSON array
     * of relative paths (e.g., "storage/reports/image.jpg").
     * We convert them to full URLs with asset() helper.
     */
    public function toArray($request): array
    {
        // Convert images array to full URLs
        $images = [];
        if (is_array($this->images) && !empty($this->images)) {
            foreach ($this->images as $imagePath) {
                $images[] = asset($imagePath);
            }
        }

        return [
            'id' => (string) $this->id,
            'user_id' => (string) $this->user_id,
            'title' => $this->title,
            'category' => $this->category?->name ?? 'Lainnya',
            'location' => $this->address,
            'description' => $this->description,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'images' => $images,
            'status' => $this->status,
            'admin_notes' => $this->admin_note,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => (string) $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ]),
            'timeline' => $this->buildTimeline(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Derive a status timeline. The schema has no dedicated history table,
     * so we present a coherent progression derived from the current status.
     */
    protected function buildTimeline(): array
    {
        $created = $this->created_at?->toIso8601String();
        $updated = $this->updated_at?->toIso8601String() ?? $created;

        $steps = [
            [
                'status' => 'received',
                'description' => 'Laporan Diterima',
                'timestamp' => $created,
            ],
        ];

        $map = [
            Report::STATUS_PROCESS => ['process', 'Dalam Proses Perbaikan'],
            Report::STATUS_DONE => ['done', 'Laporan Selesai'],
            Report::STATUS_REJECTED => ['rejected', 'Laporan Ditolak'],
        ];

        if (isset($map[$this->status])) {
            $steps[] = [
                'status' => $map[$this->status][0],
                'description' => $map[$this->status][1],
                'timestamp' => $updated,
            ];
        }

        return $steps;
    }
}
