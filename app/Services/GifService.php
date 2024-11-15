<?php 
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\Services\ApiLogService;

class GifService
{
    protected $client;
    protected $apiLogService;
    protected $apiUrl = 'https://api.giphy.com/v1/gifs/';

    public function __construct(Client $client, ApiLogService $apiLogService)
    {
        $this->client = $client;
        $this->apiLogService = $apiLogService;
    }

    public function validateGifExists(string $gifId, $userId, $requestIp)
    {
        $serviceName = 'gif_validate_exists';
        $serviceUrl = "{$this->apiUrl}{$gifId}";
        $requestPayload = [
            'api_key' => env('GIPHY_API_KEY')
        ];
    
        $result = $this->makeApiRequest($serviceUrl, $requestPayload, $userId, $requestIp, $serviceName);

        if ($result['status'] !== 200) {
            throw new \Exception('GIF not found or API error');
        }
    
        return $result['data'];
    }
    
    public function searchGifs(array $queryParams, $userId, $requestIp)
    {
        $serviceName = 'gif_search';
        $serviceUrl = "{$this->apiUrl}search";
        $requestPayload = [
            'api_key' => env('GIPHY_API_KEY'),
            'q' => $queryParams['query'],
            'limit' => $queryParams['limit'] ?? 10,
            'offset' => $queryParams['offset'] ?? 0,
        ];

        return $this->makeApiRequest($serviceUrl, $requestPayload, $userId, $requestIp, $serviceName);
    }

    public function getGifById(string $id, $userId, $requestIp)
    {
        $serviceName = 'gif_find_by_id';
        $serviceUrl = "{$this->apiUrl}{$id}";
        $requestPayload = [
            'api_key' => env('GIPHY_API_KEY')
        ];

        return $this->makeApiRequest($serviceUrl, $requestPayload, $userId, $requestIp, $serviceName);
    }

    private function makeApiRequest($serviceUrl, $requestPayload, $userId, $requestIp, $serviceName)
    {
        try {
            $response = $this->client->get($serviceUrl, ['query' => $requestPayload]);
            $responseBody = json_decode($response->getBody()->getContents(), true);
            $responseCode = $response->getStatusCode();
            $this->apiLogService->logApiInteraction(
                $userId,
                $serviceName,
                $requestPayload,
                $responseCode,
                $responseBody,
                $requestIp
            );

            return [
                'status' => $responseCode,
                'data' => $responseBody
            ];
        } catch (\Exception $e) {
            $this->apiLogService->logApiInteraction(
                $userId,
                $serviceName,
                $requestPayload,
                500,
                ['error' => $e->getMessage()],
                $requestIp
            );

            return [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
    }
}
