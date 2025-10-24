<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserRepository implements UserRepositoryInterface
{
    private \PDO $conn;

    public function __construct(private Database $db)
    {
        $this->conn = $db->getConnection();
    }

    public function create(string $name, string $email): User
    {
        $stmt = $this->conn->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
        $stmt->execute([
            ':name'  => $name,
            ':email' => $email
        ]);

        return new User($this->conn->lastInsertId(), $name, $email);
    }

    public function findAll(): array
    {
        $stmt = $this->conn->query('SELECT id, name, email FROM users');
        $users = [];

        while ($row = $stmt->fetch()) {
            $users[] = new User($row['id'], $row['name'], $row['email']);
        }

        return $users;
    }

    public function findByID(int $id): ?User
    {
        $stmt = $this->conn->prepare('SELECT id, name, email FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);

        if ($row = $stmt->fetch()) {
            $user = new User($row['id'], $row['name'], $row['email']);
            return $user;
        }

        return null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->conn->prepare('SELECT id, name, email FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);

        if ($row = $stmt->fetch()) {
            $user = new User($row['id'], $row['name'], $row['email']);
            return $user;
        }

        return null;
    }

    public function update(int $id, array $data): bool
    {
        $allowedFields = ['name', 'email'];

        $updatedFields = [];
        $bindFields = [':id' => $id];

        foreach ($allowedFields as $field) {
            if (!array_key_exists($field, $data)) continue;

            $value = $data[$field];

            $updatedFields[] = "$field = :$field";
            $bindFields[":$field"] = $value;
        }

        if (empty($updatedFields)) {
            return false;
        }

        $query = 'UPDATE users SET ' . implode(', ', $updatedFields) . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->execute($bindFields);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
