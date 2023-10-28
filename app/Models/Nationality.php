<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Nationality extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    // fields
    protected $fillable = [
        'name',
        'code',
        'flag',
        'dial_code',
        'currency',
        'currency_symbol',
    ];

    /**
     * Get all of the users for the Nationality
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'nationality_id');
    }
}
