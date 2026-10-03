<?php

class ServiceManager
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get all services.
     */
    public function getAll(): array
    {
        $services = [];

        $statement = $this->conn->prepare(
            'SELECT id, service_name, category, description, price, duration
             FROM services
             ORDER BY id DESC'
        );

        if ($statement === false) {
            return $services;
        }

        if ($statement->execute()) {
            $statement->bind_result(
                $id,
                $service_name,
                $category,
                $description,
                $price,
                $duration
            );

            while ($statement->fetch()) {
                $services[] = [
                    'id' => $id,
                    'service_name' => $service_name,
                    'category' => $category,
                    'description' => $description,
                    'price' => $price,
                    'duration' => $duration
                ];
            }
        }

        $statement->close();

        return $services;
    }

    /**
     * Get one service by ID.
     */
    public function getById(int $id): ?array
    {
        $statement = $this->conn->prepare(
            'SELECT id, service_name, category, description, price, duration
             FROM services
             WHERE id = ?'
        );

        if ($statement === false) {
            return null;
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        $statement->bind_result(
            $service_id,
            $service_name,
            $category,
            $description,
            $price,
            $duration
        );

        $service = null;

        if ($statement->fetch()) {
            $service = [
                'id' => $service_id,
                'service_name' => $service_name,
                'category' => $category,
                'description' => $description,
                'price' => $price,
                'duration' => $duration
            ];
        }

        $statement->close();

        return $service;
    }

    /**
     * Create a new service.
     */
    public function create(
        string $serviceName,
        string $category,
        string $description,
        float $price,
        int $duration
    ): bool {
        $statement = $this->conn->prepare(
            'INSERT INTO services
             (service_name, category, description, price, duration)
             VALUES (?, ?, ?, ?, ?)'
        );

        if ($statement === false) {
            return false;
        }

        $statement->bind_param(
            'sssdi',
            $serviceName,
            $category,
            $description,
            $price,
            $duration
        );

        $success = $statement->execute();

        $statement->close();

        return $success;
    }

    /**
 * Update an existing service.
 */
public function update(
    int $id,
    string $serviceName,
    string $category,
    string $description,
    float $price,
    int $duration
): bool {
    $statement = $this->conn->prepare(
        'UPDATE services
         SET service_name = ?,
             category = ?,
             description = ?,
             price = ?,
             duration = ?
         WHERE id = ?'
    );

    if ($statement === false) {
        return false;
    }

    $statement->bind_param(
        'sssdii',
        $serviceName,
        $category,
        $description,
        $price,
        $duration,
        $id
    );

    $success = $statement->execute();

    $statement->close();

    return $success;
}

    /**
     * Delete a service.
     */
    public function delete(int $id): bool
    {
        $statement = $this->conn->prepare(
            'DELETE FROM services
             WHERE id = ?'
        );

        if ($statement === false) {
            return false;
        }

        $statement->bind_param('i', $id);

        $success = $statement->execute();

        $statement->close();

        return $success;
    }
}