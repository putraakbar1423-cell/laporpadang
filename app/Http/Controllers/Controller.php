<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a 422 response in the API error envelope used by the Flutter client.
     */
    protected function validationError(Validator $validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => 'REPORT_002',
                'message' => 'Data tidak valid.',
                'details' => $validator->errors(),
            ],
        ], 422);
    }
}
