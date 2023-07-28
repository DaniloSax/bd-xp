<?php

namespace App\Core;

use App\Core\Contracts\RepositoryContract;
use App\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use stdClass;

abstract class BaseRepository implements RepositoryContract
{
    protected BaseModel|User $model;

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(string $id): Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $filters [column, value]
     */
    public function where(array $filters = []): Builder
    {
        if (count($filters) === 0) {
            return $this->model->query();
        }

        $builder = $this->model;

        foreach ($filters as $key => $value) {
            $builder = $builder->where($key, 'ilike', "%$value%");
        }

        return $builder;
    }

    public function whereFirst(string $column, $valor)
    {
    }

    public function paginate(?int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $model = $this->model;

        if (count($filters) === 0) {

            return $model->paginate($perPage);
        }

        $model = $this->where($filters);

        return $model->paginate($perPage);
    }

    public function create(array $data): Model
    {
        $created = $this->model->create($data);
        return $created;
    }

    public function update(string $id, array $data): Model
    {
        $model = $this->findById($id);
        $model->update($data);

        return $this->findById($id);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        return $model->delete();
    }

    public function orderBy($column, $order = 'DESC')
    {
        $this->model->orderBy($column, $order);
    }

    /** @param string $model namespace of the model */
    public function setModel(string $model)
    {
        $isBaseModel = is_subclass_of($model, BaseModel::class);
        $isUserModel = Str::is(User::class, $model);

        throw_if(!$isBaseModel && !$isUserModel, NotFoundHttpException::class);

        $this->model = new $model();
    }
}
