<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ErrorController extends Controller
{
    public function handleNotFound(Request $request)
    {
        return response()->json([
            'error' => 'Route not found',
            'message' => 'The requested URL does not exist.',
            'status' => 404,
        ], 404);
    }
}
