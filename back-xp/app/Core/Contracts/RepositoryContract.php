<?php

namespace App\Core\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryContract
{
    public function getAll();
    public function findById(string $id);
    public function where(array $filters);
    public function whereFirst(string $column, $valor);
    public function paginate(?int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function create(array $data): object;
    public function update(string $id, array $data): object;
    public function delete(string $id): bool;
    public function orderBy($column, $order = 'DESC');
}
