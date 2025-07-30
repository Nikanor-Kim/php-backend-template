<?php
namespace App\Services;

use App\Config\Config;
use App\Utils\Logger;
use App\Services\AmoCrmHelper;

class AmoCrmService
{


    private static function getHeaders(): array
    {
        return [
            "Authorization: Bearer " . $_ENV['API_TOKEN'],
            "Content-Type: application/json",
        ];
    }

    private static function sendRequest($url, $method, $payload)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => self::getHeaders(),
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            Logger::log("Ошибка curl: $err", 'curl_error');
            return false;
        }

        if ($httpCode >= 400) {
            Logger::log("Ошибка AmoCRM ($httpCode): $response", 'amocrm_error');
            return false;
        }

        return json_decode($response, true);
    }

    public static function sendNoteToAmoCRM($lead_id, $message)
    {
        $subdomain = $_ENV['SUBDOMAIN'];
        // Logger::Log($lead_id);
        // Logger::Log($message);

        $url = "https://$subdomain.amocrm.ru/api/v4/leads/$lead_id/notes";


        $payload = [
            [
                "note_type" => "service_message", // Тип примечания
                "params" => [
                    "text" => $message,
                    "service" => "amorequest"
                ]
            ]
        ];

        return self::sendRequest($url, 'POST', $payload);
    }

    public static function updateLead($leadData, $lead_id)
    {
        $subdomain = $_ENV['SUBDOMAIN'];
        $url = "https://$subdomain.amocrm.ru/api/v4/leads/$lead_id";
        return self::sendRequest($url, 'PATCH', $leadData);
    }
}