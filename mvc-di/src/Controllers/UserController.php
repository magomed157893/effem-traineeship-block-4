<?php

namespace App\Controllers;

use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function __construct(private UserService $service) {}

    public function index(): Response
    {
        $users = $this->service->getUsers();
        ob_start();
        include dirname(__DIR__) . '/../views/users.php';
        return new Response(ob_get_clean());
    }

    public function getAll(): JsonResponse
    {
        $users = $this->service->getUsers();
        return new JsonResponse($users);
    }

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data) || empty($data)) {
            throw new \InvalidArgumentException('No data available', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->service->createUser($data);
        return new JsonResponse($user);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data) || empty($data)) {
            throw new \InvalidArgumentException('No data available', Response::HTTP_BAD_REQUEST);
        }

        $updated = $this->service->updateUser($id, $data);
        return new JsonResponse(['updated' => $updated]);
    }

    public function delete(int $id): JsonResponse
    {
        $deleted = $this->service->deleteUser($id);
        return new JsonResponse(['deleted' => $deleted]);
    }
}
