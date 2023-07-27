<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ServiceContract
{
    public function index(?array $filters): Collection|LengthAwarePaginator;
    public function show(string|int $id): Model;
    public function store(array $data): object;
    public function update(string|int $id, array $data): object;
    public function destroy(string|int $id): bool;
}
