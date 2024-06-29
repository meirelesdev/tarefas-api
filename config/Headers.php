<?php

declare(strict_types=1);

namespace Config;


class Headers
{
  public function __construct()
  {
    // Permitir requisições de qualquer origem
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    // Responder a requisições OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      http_response_code(200);
      exit;
    }
  }
}