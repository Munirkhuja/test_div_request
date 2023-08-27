<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestModel extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'Active';
    const STATUS_RESOLVED = 'Resolved';

    protected $fillable = [
        'name',
        'email',
        'status',
        'message',
        'comment',
        'comment_user_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function comment_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'comment_user_id');
    }
}
