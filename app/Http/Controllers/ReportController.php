<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * GET /api/v1/reports
     * Paginated list with optional category / status / search filters.
     */
    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min($limit, 100));

        $reports = Report::query()
            ->with(['user', 'category'])
            ->ofCategory($request->query('category'))
            ->ofStatus($request->query('status'))
            ->search($request->query('search'))
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => [
                'reports' => ReportResource::collection($reports->items()),
                'pagination' => [
                    'current_page' => $reports->currentPage(),
                    'total_pages' => $reports->lastPage(),
                    'total_items' => $reports->total(),
                    'items_per_page' => $reports->perPage(),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/reports/{id}
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $report = Report::with(['user', 'category', 'comments.user'])
            ->find($id);

        if (! $report) {
            return $this->notFound('Laporan tidak ditemukan.');
        }

        return response()->json([
            'success' => true,
            'data' => (new ReportResource($report))->toArray($request),
        ]);
    }

    /**
     * POST /api/v1/reports
     * 
     * Supports 2 formats:
     * 1. JSON with base64 images: { "images": ["data:image/jpeg;base64,...", ...] }
     * 2. Multipart form-data with file uploads
     */
    public function store(Request $request): JsonResponse
    {
        // Log incoming request for debugging
        \Log::info('Report Store Request', [
            'content_type' => $request->header('Content-Type'),
            'has_images_field' => $request->has('images'),
            'images_type' => $request->has('images') ? gettype($request->input('images')) : 'none',
            'images_sample' => $request->has('images') && is_array($request->input('images')) 
                ? substr(json_encode(array_slice($request->input('images'), 0, 1)), 0, 100) 
                : 'n/a',
        ]);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'exists:categories,name'],
            'location' => ['required', 'string'],
            'description' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            // Accept both file uploads and base64 strings
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['nullable'], // Will validate manually below
        ]);

        if ($validator->fails()) {
            \Log::error('Validation Failed', ['errors' => $validator->errors()->toArray()]);
            return $this->validationError($validator);
        }

        $category = Category::where('name', $request->category)->first();

        // Handle image uploads - support both base64 and file uploads
        $imagePaths = [];
        
        if ($request->has('images') && is_array($request->input('images'))) {
            \Log::info('Processing images array', ['count' => count($request->input('images'))]);
            
            foreach ($request->input('images') as $index => $imageData) {
                // Check if it's a base64 string (from Flutter JSON request)
                if (is_string($imageData) && (
                    str_starts_with($imageData, 'data:image/') || 
                    str_starts_with($imageData, '/9j/') || // JPEG base64 start
                    str_starts_with($imageData, 'iVBOR')   // PNG base64 start
                )) {
                    try {
                        // Extract base64 data
                        if (str_contains($imageData, 'base64,')) {
                            [$type, $imageData] = explode('base64,', $imageData, 2);
                            
                            // Get image extension from MIME type
                            preg_match('/image\/(\w+)/', $type, $matches);
                            $extension = $matches[1] ?? 'jpg';
                        } else {
                            // Pure base64 without data URI
                            $extension = 'jpg'; // Default to jpg
                        }
                        
                        // Decode base64
                        $imageContent = base64_decode($imageData);
                        
                        if ($imageContent === false || strlen($imageContent) < 100) {
                            \Log::warning('Invalid base64 image', ['index' => $index]);
                            continue;
                        }
                        
                        // Generate filename and save
                        $filename = time() . '_' . uniqid() . '.' . $extension;
                        $path = 'reports/' . $filename;
                        
                        // Store file in storage/app/public/reports
                        \Storage::disk('public')->put($path, $imageContent);
                        
                        $imagePaths[] = 'storage/' . $path;
                        
                        \Log::info('Base64 image saved', [
                            'index' => $index,
                            'filename' => $filename,
                            'size' => strlen($imageContent),
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to process base64 image', [
                            'index' => $index,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                // Check if it's a file upload (from multipart/form-data)
                elseif (is_object($imageData) && method_exists($imageData, 'isValid')) {
                    if ($imageData->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $imageData->getClientOriginalExtension();
                        $path = $imageData->storeAs('reports', $filename, 'public');
                        $imagePaths[] = 'storage/' . $path;
                        
                        \Log::info('File upload saved', [
                            'index' => $index,
                            'filename' => $filename,
                        ]);
                    }
                }
            }
        }

        \Log::info('Final image paths', ['count' => count($imagePaths), 'paths' => $imagePaths]);

        $report = Report::create([
            'user_id' => $request->user()->id,
            'category_id' => $category->id,
            'district_id' => null,
            'title' => $request->title,
            'description' => $request->description,
            'images' => empty($imagePaths) ? null : $imagePaths, // Store as JSON array or null
            'address' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => Report::STATUS_PENDING,
        ]);

        $report->load(['user', 'category']);

        \Log::info('Report created', [
            'id' => $report->id,
            'images_count' => $report->images ? count($report->images) : 0,
            'images_in_db' => $report->images,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat.',
            'data' => (new ReportResource($report))->toArray($request),
        ], 201);
    }

    /**
     * PUT /api/v1/reports/{id}
     * Users may only edit their own report while it is still pending.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $report = Report::find($id);

        if (! $report) {
            return $this->notFound('Laporan tidak ditemukan.');
        }

        if ($report->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'REPORT_003', 'message' => 'Anda tidak dapat mengubah laporan ini.'],
            ], 403);
        }

        if ($report->status !== Report::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'REPORT_003', 'message' => 'Laporan hanya dapat diubah saat status pending.'],
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $report->update($request->only(['title', 'description']));
        $report->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui.',
            'data' => (new ReportResource($report))->toArray($request),
        ]);
    }

    /**
     * DELETE /api/v1/reports/{id}
     * Users may only delete their own report while it is still pending.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $report = Report::find($id);

        if (! $report) {
            return $this->notFound('Laporan tidak ditemukan.');
        }

        if ($report->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'REPORT_004', 'message' => 'Anda tidak dapat menghapus laporan ini.'],
            ], 403);
        }

        if ($report->status !== Report::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'REPORT_004', 'message' => 'Laporan hanya dapat dihapus saat status pending.'],
            ], 422);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }

    protected function notFound(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => ['code' => 'REPORT_001', 'message' => $message],
        ], 404);
    }
}
