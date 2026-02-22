<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlateRecognizerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PlateRecognizerController extends Controller
{
    private PlateRecognizerService $plateRecognizerService;

    public function __construct(PlateRecognizerService $plateRecognizerService)
    {
        $this->plateRecognizerService = $plateRecognizerService;
    }

    /**
     * Scan license plate from uploaded image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function scanPlate(Request $request): JsonResponse
    {
        try {
            // Validate image file
            $request->validate([
                'image' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png',
                    'max:5120', // 5MB in KB
                ],
            ], [
                'image.required' => 'Gambar wajib diunggah',
                'image.image' => 'File harus berupa gambar',
                'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
                'image.max' => 'Ukuran gambar maksimal 5MB',
            ]);

            $image = $request->file('image');
            $includeRawResponse = $request->boolean('debug', false);

            // Scan plate using service
            $result = $this->plateRecognizerService->scanPlate($image, $includeRawResponse);

            // Return JSON response
            return response()->json([
                'success' => true,
                'plate_number' => $result['plate_number'],
                'confidence' => $result['confidence'],
                'valid' => $result['valid'],
                'message' => $result['message'],
                'raw_response' => $result['raw_response'],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Plate Recognizer Controller Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'plate_number' => null,
                'confidence' => 0,
                'valid' => false,
            ], 500);
        }
    }
}

