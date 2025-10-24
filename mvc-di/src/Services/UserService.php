<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function getUsers(): array
    {
        return $this->repository->findAll();
    }

    public function createUser(array $data): User
    {
        $name = $data['name'] ?? null;
        if (!isset($name) || !is_string($name) || empty(trim($name)) || strlen($name) > 255) {
            throw new \InvalidArgumentException('Name is not available or incorrect', Response::HTTP_BAD_REQUEST);
        }

        $email = $data['email'] ?? null;
        if (!isset($email) || !is_string($email) || empty(trim($email)) || strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email is not available or incorrect', Response::HTTP_BAD_REQUEST);
        }

        if ($this->repository->findByEmail($email)) {
            throw new \DomainException('User already exists', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->repository->create($name, $email);
    }

    public function updateUser(int $id, array $data): bool
    {
        if (!$this->repository->findByID($id)) {
            throw new \DomainException("User with id {$id} not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $name = $data['name'] ?? null;
        if (isset($name) && (!is_string($name) || empty(trim($name)) || strlen($name) > 255)) {
            throw new \InvalidArgumentException('Name is not available or incorrect', Response::HTTP_BAD_REQUEST);
        }

        $email = $data['email'] ?? null;
        if (isset($email) && (!is_string($email) || empty(trim($email)) || strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            throw new \InvalidArgumentException('Email is not available or incorrect', Response::HTTP_BAD_REQUEST);
        }

        if (isset($email) && $this->repository->findByEmail($email)) {
            throw new \DomainException('Email already exists', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$this->repository->update($id, $data)) {
            return false;
        }

        return true;
    }

    public function deleteUser(int $id): bool
    {
        if (!$this->repository->delete($id)) {
            return false;
        }

        return true;
    }
}
