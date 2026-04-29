<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    public static function json(): array
    {
        $rawBody = file_get_contents('php://input') ?: '';
        $data = json_decode($rawBody, true);

        return is_array($data) ? $data : [];
    }
}
