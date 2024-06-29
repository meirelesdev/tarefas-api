<?php

namespace App\Controllers;

use App\Handlers\RequestHandler;
use App\Handlers\ResponseHandler;
use App\Models\Task;
use Config\Database;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de Tarefas",
 *     version="1.0.0",
 *     description="API para gerenciar tarefas"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/ipm-agenda/backend/v1/api",
 *     description="Servidor local"
 * )
 */
class TaskController
{
    private $db;
    private $task;
    private $response;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
        $this->task = new Task($this->db);
        $this->response = new ResponseHandler();
    }

    /**
     * @OA\Get(
     *     path="/tarefas",
     *     summary="Lista todas as tarefas",
     *
     *     @OA\Response(response="200", description="Lista de tarefas")
     * )
     */
    public function getAll()
    {
        $task = $this->task->list();
        echo json_encode($task);
    }

    /**
     * @OA\Get(
     *     path="/tarefas/{id}",
     *     summary="Obtém uma tarefa pelo ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response="200", description="Dados da tarefa"),
     *     @OA\Response(response="404", description="Tarefa não encontrada")
     * )
     */
    public function getTaskById(RequestHandler $request) {
        $id = $request->getParam('id');
        $task = $this->task->getById($id);

        if ($task) {
            $this->response->send($task);
        } else {
            $this->response->status(404)->send(["mensagem" => "Tarefa não encontrada."]);
        }
    }

    /**
     * @OA\Post(
     *     path="/tarefas",
     *     summary="Cria uma nova tarefa",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title","description","status","due_date"},
     *
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="desacription", type="string"),
     *             @OA\Property(property="due_date", type="string", format="date")
     *         )
     *     ),
     *
     *     @OA\Response(response="201", description="Tarefa criada com sucesso")
     * )
     */
    public function store(RequestHandler $request)
    {
        $body = $request->getBody();
        $this->task->validateData($body);
        
        $this->task->title = $body['title'];
        $this->task->description = $body['description'];
        $this->task->status = 'pending';
        $this->task->due_date = $body['due_date'];

        if ($this->task->create()) {
            $this->response->send(['mensagem' => 'Tarefa criada com sucesso.']);
        } else {
            $this->response->send(['mensagem' => 'Não foi possível criar a tarefa.']);
        }
    }

    /**
     * @OA\Put(
     *     path="/tarefas",
     *     summary="Atualiza uma tarefa existente",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"id","title","description","status","due_date"},
     *
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="due_date", type="string", format="date")
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Tarefa atualizada com sucesso")
     * )
     */
    public function update(RequestHandler $request) {
        $this->task->id = $request->getParam('id');
        $body = $request->getBody();
        $this->task->title = $body['title'];
        $this->task->description = $body['description'];
        $this->task->status = $body['status'];
        $this->task->due_date = $body['due_date'];

        if ($this->task->update()) {
            $this->response->send(['mensagem' => 'Tarefa atualizada com sucesso.']);
        } else {
            $this->response->send(['mensagem' => 'Não foi possível atualizar a tarefa.']);
        }
    }

    /**
     * @OA\Delete(
     *     path="/tarefas/{id}",
     *     summary="Deleta uma tarefa existente",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response="200", description="Tarefa deletada com sucesso")
     * )
     */
    public function delete(RequestHandler $request)
    {
        $this->task->id = $request->getParam('id');

        if ($this->task->delete()) {
            $this->response->send(['mensagem' => 'Tarefa deletada com sucesso.']);
        } else {
            $this->response->send(['mensagem' => 'Não foi possível deletar a tarefa.']);
        }
    }
}