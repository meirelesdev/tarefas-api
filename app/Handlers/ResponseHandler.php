<?php

declare(strict_types=1);

namespace App\Handlers;


class ResponseHandler
{
  public function status(int $status): self
  {
    http_response_code($status);
    return $this;
  }
  
  public function send(array $array): void
  {
    echo json_encode($array);
  }
}