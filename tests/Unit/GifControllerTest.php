<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GifService;
use Mockery;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Passport\HasApiTokens;


class GifControllerTest extends TestCase
{
    protected $bearerToken = 'Bearer fake_token_example';

    public function testSearchGifsSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $gifService = Mockery::mock(GifService::class);
        $gifService->shouldReceive('searchGifs')
                   ->once()
                   ->with(['query' => 'funny', 'limit' => 10, 'offset' => 0], $user->id, '127.0.0.1')
                   ->andReturn(['status' => 200, 'data' => ['gif1', 'gif2']]);
    
        $this->app->instance(GifService::class, $gifService);
    
        $response = $this->getJson('api/v1/gifs/search?query=funny', [
            'X-Forwarded-For' => '127.0.0.1',
        ]);
    
        $response->assertStatus(200);
        $response->assertJson(['gif1', 'gif2']);
    }
    

    public function testSearchGifsValidationFailure()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->getJson('api/v1/gifs/search?query=');

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Validation failed']);
    }
}
