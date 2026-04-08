<?php

namespace App\AI;

use RuntimeException;

class AIClient
{
    private string $apiKey;
    private string $apiBase;
    private string $model;

    public function __construct()
    {
        $this->apiKey  = config('services.ai.key');
        $this->apiBase = rtrim(config('services.ai.base'), '/');
        $this->model   = config('services.ai.model');
    }

    public function generate(string $prompt): array
    {
        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You output JSON only. No explanations. No markdown.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.2,
            'response_format' => [
                'type' => 'json_object'
            ]
        ];

        $response = $this->post('/chat/completions', $payload);

        if (
            !isset($response['choices'][0]['message']['content'])
        ) {
            throw new RuntimeException('AI response missing content');
        }

        $json = json_decode(
            $response['choices'][0]['message']['content'],
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'AI returned invalid JSON: ' . json_last_error_msg()
            );
        }

        return $json;
    }

    private function post(string $path, array $payload): array
    {
        $ch = curl_init($this->apiBase . $path);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $raw = curl_exec($ch);

        if ($raw === false) {
            throw new RuntimeException('AI request failed: ' . curl_error($ch));
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException(
                "AI API error ({$status}): {$raw}"
            );
        }

        return json_decode($raw, true);
    }
}
