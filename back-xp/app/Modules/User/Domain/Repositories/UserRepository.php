<?php

namespace App\Modules\User\Domain\Repositories;

use App\Core\BaseRepository;
use App\Modules\User\Domain\Models\Address;
use App\Modules\User\Domain\Models\People;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function updateOrCreate(array $data, string $method): Model
    {
        return DB::transaction(function () use ($data, $method) {

            $inputs = collect($data)->except('people')->toArray();

            $user = array_key_exists('id', $data) ? $this->model->find($data['id']) : $this->model->create($inputs);

            if ($method === 'update') {
                $user->update($inputs);
            }

            if (array_key_exists('people', $data) && array_key_exists('address', $data['people'])) {

                $data['people'] = array_merge($data['people'], ['user_id' => $user->id]);

                if ($method === 'update') {

                    $user->people->$method($data['people']);
                } else {

                    $user->people()->$method($data['people']);
                }

                foreach ($data['people']['address'] as $address) {

                    $address['addresseable_type'] = People::class;
                    $address['addresseable_id'] = $user->people->id;

                    if ($method === 'create') {

                        $user->people->address[] = Address::create($address);
                    }

                    if ($method === 'update') {

                        $user->people->address->where('id', $address['id'])->first()->update($address);
                    }
                }
            }

            return $user;
        });
    }

    public function create(array $data): Model
    {
        $created = $this->updateOrCreate($data, 'create');

        return $created;
    }

    public function update(string $id, array $data): Model
    {
        $updated = $this->updateOrCreate(array_merge($data, ['id' => $id]), 'update');

        return $updated;
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);

        foreach ($model->people->address as $key => $address) {
            $address->delete();
        }

        return $model->delete();
    }
}
