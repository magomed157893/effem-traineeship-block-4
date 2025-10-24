<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(string $name, string $email): User;

    public function findAll(): array;

    public function findByID(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
