<?php

namespace App\Services;

use App\Models\ApiLog;

class ApiLogService
{
    public function logApiInteraction($userId, $serviceName, $requestPayload, $responseCode, $responseBody, $originIp)
    {
        ApiLog::create([
            'user_id' => $userId,
            'service_name' => $serviceName,
            'request_payload' => json_encode($requestPayload),
            'response_code' => $responseCode,
            'response_body' => json_encode($responseBody),
            'origin_ip' => $originIp,
        ]);
    }
}
