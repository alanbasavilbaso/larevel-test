<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;
use App\Services\FavoriteGifService;
use App\Services\ApiLogService;
use App\Services\GifService;

class FavoriteGifController extends Controller
{
    protected $favoriteGifService;
    protected $apiLogService;
    protected $gifService;

    public function __construct(FavoriteGifService $favoriteGifService, ApiLogService $apiLogService, GifService $gifService)
    {
        $this->favoriteGifService = $favoriteGifService;
        $this->apiLogService = $apiLogService;
        $this->gifService = $gifService;
    }

    public function store(Request $request)
    {
        $serviceName = 'favorite_gif_store';
        $validator = Validator::make($request->all(), [
            'gif_id' => 'required|string|regex:/^[a-zA-Z0-9]+$/',
            'alias' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed'
            ], 422);
        }

        $userId = auth()->id();
        $requestPayload = $request->only(['gif_id', 'alias']);
        $requestIp = $request->ip();
        $gifId = $request->gif_id;

        try {
            $this->gifService->validateGifExists($gifId, $userId, $requestIp);
            $favoriteGif = $this->favoriteGifService->storeFavoriteGif($userId, $gifId, $request->alias);

            $responseBody = ['message' => 'Favorite GIF saved successfully.'];
            $responseCode = 201;
            $this->apiLogService->logApiInteraction(
                $userId,
                $serviceName,
                $requestPayload,
                $responseCode,
                $responseBody,
                $requestIp
            );

            return response()->json($responseBody, $responseCode);
        } catch (QueryException $e) {
            return $this->handleException($e, $userId, $serviceName, $requestPayload, $requestIp);
        } catch (Exception $e) {
            return $this->handleException($e, $userId, $serviceName, $requestPayload, $requestIp);
        }
    }

    protected function handleException($e, $userId, $serviceName, $requestPayload, $requestIp)
    {
        $responseCode = 500;
        $responseBody = [
            'error' => 'Error saving favorite GIF',
            'details' => $e->getMessage(),
        ];

        $this->apiLogService->logApiInteraction($userId, $serviceName, $requestPayload, $responseCode, $responseBody, $requestIp);
        return response()->json($responseBody, $responseCode);
    }
}