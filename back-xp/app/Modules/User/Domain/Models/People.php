<?php

namespace App\Modules\User\Domain\Models;

use App\Core\BaseModel;
use Database\Factories\PeopleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class People extends BaseModel
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'peoples';

    protected $fillable = [
        'user_id',
        'name',
        'cpf',
        'rg',
        'birthday'
    ];

    protected $with = ['address'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return PeopleFactory::new();
    }

    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, 'addresseable');
    }
}
