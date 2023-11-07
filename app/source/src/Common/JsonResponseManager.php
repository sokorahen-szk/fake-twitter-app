<?php

namespace Src\Common;

class JsonResponseManager
{
    public static function response(int $statusCode, array $data = [])
    {
        $encodedJsonData = json_encode(self::toResponseTemplate($data, 200));
        if (json_last_error() !== JSON_ERROR_NONE || is_bool($encodedJsonData)) {
            self::generateResponse(
                json_encode(self::toErrorResponseTemplate("json parse error", 500)),
                500
            );
        }

        if ($statusCode !== 200) {
            if (count($data) !== 1) {
                self::generateResponse(
                    json_encode(self::toErrorResponseTemplate("error", $statusCode)),
                    $statusCode
                );
            }
            self::generateResponse(
                json_encode(self::toErrorResponseTemplate($data, $statusCode)),
                $statusCode
            );
        }

        self::generateResponse(
            $encodedJsonData,
            200
        );
    }

    private static function toErrorResponseTemplate(mixed $mixedMessages, int $statusCode): array
    {
        $messages = [];
        foreach ($mixedMessages as $k => $v) {
            $messages[] = $v;
        }

        return [
            "status_code" => $statusCode,
            "messages" => $messages,
        ];
    }

    private static function toResponseTemplate(array $data, int $statusCode): array
    {
        return [
            "status_code" => $statusCode,
            "data" => $data,
        ];
    }

    private static function generateResponse(string $data, int $statusCode): void
    {
        self::setHeader($statusCode);
        print $data;
        exit;
    }

    private static function setHeader(int $statusCode): void
    {
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($statusCode);
    }
}
