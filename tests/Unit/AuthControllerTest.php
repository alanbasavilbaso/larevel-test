<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;
use Mockery;
use Illuminate\Validation\ValidationException;

class AuthControllerTest extends TestCase
{
    // use RefreshDatabase;
    protected $validEmail = 'alan@example.com';
    protected $validPassword = 'password123';
    protected $invalidEmail = 'invalid_email';
    protected $invalidPassword = 'short';
    protected $wrongPassword = 'wrongpassword';
    protected $apiUrl = 'api/v1/login';
    protected $requestIp = '127.0.0.1';

    public function testLoginSuccess()
    {
        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('login')
                    ->once()
                    ->with($this->validEmail, $this->validPassword, $this->requestIp)
                    ->andReturn('fake_token');  
        $this->app->instance(AuthService::class, $authService);
   
        $response = $this->postJson($this->apiUrl, [
            'email' => $this->validEmail,
            'password' => $this->validPassword,
        ], ['X-Forwarded-For' => $this->requestIp]);

        $response->assertStatus(200);
        $response->assertJson([
            'token' => 'fake_token',
            'expires_in' => 1800,
        ]);
    }

    public function testLoginValidationFailure()
    {
        $response = $this->postJson($this->apiUrl, [
            'email' => $this->invalidEmail,
            'password' => $this->invalidPassword,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Validation failed']);
    }

    public function testLoginInvalidCredentials()
    {
        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('login')
                    ->once()
                    ->with($this->validEmail, $this->wrongPassword, $this->requestIp)
                    ->andThrow(ValidationException::withMessages(['Invalid credentials']));

        $this->app->instance(AuthService::class, $authService);

        $response = $this->postJson($this->apiUrl, [
            'email' => $this->validEmail,
            'password' => $this->wrongPassword,
        ], ['X-Forwarded-For' => $this->requestIp]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Invalid credentials']);
    }
}
