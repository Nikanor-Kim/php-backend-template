<?php
namespace App\Services;

use App\Utils\Logger;
use App\Utils\ResultBuilder;

class SenseiService
{
    private static function getHeaders(): array
    {
        return [
            "X-Auth-Sensei-Token:" . $_ENV['SENSEI_TOKEN'],
            "Content-Type: application/json", // ← вот это обязательно
        ];
    }

    public static function startCalculationProcess($lead_id, $pricing_name, $pricing_account)
    {

        $process_id = $_ENV['CALCULATION_PROCESS_ID'];
        $url = "https://api.sensei.plus/v1/process/start/$process_id";
        $data = [
            'data' => [
                [
                    'entity_id' => $lead_id,
                    'entity_type' => 1,
                ]
            ],
            "param_values" => [
                "local" => [
                    ["name" => "pricing_name", "value" => $pricing_name],
                    ["name" => "pricing_account", "value" => $pricing_account],
                ]
            ]
        ];
        // Logger::debug(self::getHeaders(), 'headers');
        // Logger::debug(json_encode($data, JSON_UNESCAPED_UNICODE), 'json_body');

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => self::getHeaders(),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // Logger::debug($response, 'response');

        if ($err) {
            return ResultBuilder::error($err);
        }

        if ($httpCode >= 400) {
            return ResultBuilder::error("($httpCode): $response");
        }
        return ResultBuilder::success("Процесс запущен");


    }
}