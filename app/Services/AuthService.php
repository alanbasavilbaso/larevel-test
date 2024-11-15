<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Services\ApiLogService;

class AuthService
{
    protected $apiLogService;

    public function __construct(ApiLogService $apiLogService)
    {
        $this->apiLogService = $apiLogService;
    }

    public function login(string $email, string $password, string $requestIp)
    {
        $requestPayload = ['email' => $email];

        try {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::user();
                $token = $user->createToken('API Token')->accessToken;

                $this->apiLogService->logApiInteraction(
                    $user->id,
                    'login',
                    $requestPayload,
                    200,
                    ['token' => $token],
                    $requestIp
                );

                return $token;
            }

            $this->apiLogService->logApiInteraction(
                null,
                'login',
                $requestPayload,
                401,
                ['error' => 'Unauthorized'],
                $requestIp
            );
            
            throw ValidationException::withMessages(['Invalid credentials']);
        } catch (ValidationException $e) {
            $this->apiLogService->logApiInteraction(
                null,
                'login',
                $requestPayload,
                422,
                ['error' => 'Invalid credentials', 'details' => $e->getMessage()],
                $requestIp
            );
            throw $e;
        } catch (Throwable $e) {
            $this->apiLogService->logApiInteraction(
                null,
                'login',
                $requestPayload,
                500,
                ['error' => 'Unexpected error occurred', 'details' => $e->getMessage()],
                $requestIp
            );
            throw $e;
        }
    }
}
