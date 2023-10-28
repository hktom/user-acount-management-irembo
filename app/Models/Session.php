<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    // fields
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'token',
        'action',
        'expires_at'
    ];

    // table
    protected $table = 'logins';

    /**
     * Get the user that owns the Session
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
