<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GifService;
use Illuminate\Support\Facades\Validator;

class GifController extends Controller
{
    protected $gifService;

    public function __construct(GifService $gifService)
    {
        $this->gifService = $gifService;
    }

    public function search(Request $request)
    {
        var_dump(auth()->id());exit;
        $validator = Validator::make($request->all(), [
            'query' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed'
            ], 422);
        }

        $queryParams = [
            'query' => $request->query('query'),
            'limit' => $request->limit ?? 10,
            'offset' => $request->offset ?? 0,
        ];

        $result = $this->gifService->searchGifs($queryParams, auth()->id(), $request->ip());

        if ($result['status'] === 200) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => 'Service error', 'message' => $result['error']], $result['status']);
        }
    }

    public function show($id, Request $request)
    {
        $result = $this->gifService->getGifById($id, auth()->id(), $request->ip());

        if ($result['status'] === 200) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => 'Service error', 'message' => $result['error']], $result['status']);
        }
    }
}
