<?php 
namespace App\Services;

use App\Models\FavoriteGif;

class FavoriteGifService
{
    public function storeFavoriteGif(int $userId, string $gifId, ?string $alias)
    { 
        return FavoriteGif::firstOrCreate(
            ['user_id' => $userId, 'gif_id' => $gifId],
            ['alias' => $alias]
        );
    }
}
