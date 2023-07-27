<?php

namespace App\Core;

use App\Core\Contracts\ServiceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService implements ServiceContract
{
    public BaseRepository $repository;

    public function index(?array $filters = []): Collection|LengthAwarePaginator
    {
        // return $this->repository->where($filters)->get();
        return $this->repository->paginate(filters: $filters);
    }

    public function show(string|int $id): Model
    {
        return $this->repository->findById($id);
    }

    public function store(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(string|int $id, array $data): object
    {
        return $this->repository->update($id, $data);
    }

    public function destroy(string|int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * @param string $repository namespace of the repository
     * @param string $model namespace of the model
     */
    public function setRepository(string $repository, string $model)
    {
        $isBaseRepository = is_subclass_of($repository, BaseRepository::class);

        throw_if(!$isBaseRepository, NotFoundHttpException::class);

        $this->repository = new $repository();
        $this->repository->setModel($model);
    }
}
