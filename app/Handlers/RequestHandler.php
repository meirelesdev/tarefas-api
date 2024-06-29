<?php

declare(strict_types=1);

namespace App\Handlers;


class  RequestHandler
{

  private array $params;
  private string $body;

  public function __construct(string $body= '', array $params = [])
  {
    $this->body = $body;
    $this->params = $params;    
  }

  public function getBody(): array
  {
    return json_decode($this->body, true);
  }

  public function getParam(string $key) 
  {
    return $this->params[$key]?? null;
  }

}