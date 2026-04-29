<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;

final class HomeController
{
    public function index(): void
    {
        $html = file_get_contents(__DIR__ . '/../../public/views/home.html') ?: '<h1>Library API</h1>';
        Response::html($html);
    }
}
