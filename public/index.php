<?php

require '../vendor/autoload.php';

use App\Controllers\TaskController;
use App\Handlers\ExceptionHandler;
use App\Handlers\RequestHandler;
use App\Handlers\ResponseHandler;
use Config\Database;
use Config\Headers;

new Headers();

require_once '../app/Routers/Router.php';

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

try {
  $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
  $response = new ResponseHandler();

  switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
      $response->status(404)->send(["mensagem" => "Rota não encontrada."]);
      break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
      $allowedMethods = $routeInfo[1];
      $response->status(405)->send(["mensagem" => "Método não permitido."]);
      break;
    case \FastRoute\Dispatcher::FOUND:
      $handler = $routeInfo[1];
      $vars = $routeInfo[2];
      $id = '';
      if(isset($vars['id'])) {
        $id = $vars['id'];
      }
      $database = new Database();
      $controller = new TaskController($database);
      
      $body = file_get_contents("php://input");
      $request = new RequestHandler( $body, ['id' => $id]);
      switch ($handler) {
        case 'list':
          $controller->getAll();
          break;
        case 'getOne':
          $controller->getTaskById($request);
          break;
        case 'create':
          $controller->store($request);
          break;
        case 'update':
          $controller->update($request);
          break;
        case 'delete':
          $controller->delete($request);
          break;
        case 'swagger-json':
          require 'swagger.php';
          break;
        case 'swagger':
          require 'swagger-ui.php';
          break;
        default:
          $response->status(404)->send(["mensagem" => "Rota não encontrada."]);
          break;
      }
      break;
  }
} catch (ExceptionHandler $e) {
  $response->status(400)->send(["erro" => $e->getMessage()]);
} catch (Exception $e) {
  $response->status(500)->send(["erro" => "Ocorreu um erro no servidor: " . $e->getMessage()]);
}