<?php

use function FastRoute\simpleDispatcher;
use Dotenv\Dotenv;
use FastRoute\RouteCollector;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$basePath = $_ENV['BASE_PATH'];

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($basePath) {
  $r->get($basePath . '/', function() use($basePath){
    echo $basePath ." Entrou aqui";
    // exit;
    // header('Location: http://localhost/ipm-agenda/backend/v1/api/docs');
  });
  $r->get($basePath . '/tarefas', 'list');
  $r->put($basePath . '/tarefas/{id:\d+}', 'update');
  $r->post($basePath . '/tarefas', 'create');
  $r->delete($basePath . '/tarefas/{id:\d+}', 'delete');
  $r->get($basePath . '/tarefas/{id:\d+}', 'getOne');
  $r->get($basePath . '/swagger', 'swagger-json');
  $r->get($basePath . '/docs', 'swagger');
});