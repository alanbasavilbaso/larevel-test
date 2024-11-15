<?php 
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\FavoriteGifService;
use App\Services\ApiLogService;
use App\Services\GifService;
use Mockery;
use Illuminate\Support\Facades\Artisan;

class FavoriteGifControllerTest extends TestCase
{
    public function testStoreFavoriteGifSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $favoriteGifService = Mockery::mock(FavoriteGifService::class);
        $apiLogService = Mockery::mock(ApiLogService::class);
        $gifService = Mockery::mock(GifService::class);

        $favoriteGifService->shouldReceive('storeFavoriteGif')
                        ->once()
                        ->with($user->id, 'pkqnVgAiYQx2w', 'baby shark')
                        ->andReturn(true);

        $apiLogService->shouldReceive('logApiInteraction')
                    ->once()
                    ->with(
                        $user->id,
                        'favorite_gif_store',
                        ['gif_id' => 'pkqnVgAiYQx2w', 'alias' => 'baby shark'],
                        201,
                        Mockery::type('array'),
                        '127.0.0.1' 
                    )
                    ->andReturn(true);

        $gifService->shouldReceive('validateGifExists')
                ->once()
                ->with('pkqnVgAiYQx2w', $user->id, '127.0.0.1') 
                ->andReturn(true);

        $this->app->instance(FavoriteGifService::class, $favoriteGifService);
        $this->app->instance(ApiLogService::class, $apiLogService);
        $this->app->instance(GifService::class, $gifService);

        $response = $this->postJson('api/v1/gifs/favorite', [
            'gif_id' => 'pkqnVgAiYQx2w',
            'alias' => 'baby shark',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Favorite GIF saved successfully.']);
    }

    public function testStoreFavoriteGifValidationFailure()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');


        $response = $this->postJson('api/v1/gifs/favorite', [
            'gif_id' => '', // gif_id vacío (es inválido)
            'alias' => 'baby shark', // Alias válido
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => 'Validation failed',
        ]);
    }

}
