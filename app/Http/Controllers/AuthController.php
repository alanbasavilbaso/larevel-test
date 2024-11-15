<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed'
            ], 422);
        }

        $requestIp = $request->ip();

        try {
            $token = $this->authService->login($request->email, $request->password, $requestIp);

            return response()->json([
                'token' => $token,
                'expires_in' => 30 * 60 // 30 minutos
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Invalid credentials'], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unexpected error occurred', 'details' => $e->getMessage()], 500);
        }
    }
}
