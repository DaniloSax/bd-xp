<?php

namespace App\Modules\User\Domain\Models;

use App\Core\BaseModel;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends BaseModel
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'adresses';

    protected $fillable = [
        'people_id',
        'street',
        'number',
        'complement',
        'neighborhoods',
        'country',
        'state',
        'cep',
        'addresseable_type',
        'addresseable_id'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return AddressFactory::new();
    }

    public function addresseable(): MorphTo
    {
        return $this->morphTo();
    }
}
