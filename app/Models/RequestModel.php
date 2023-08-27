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
    protected $dateFormat = 'Y-m-d H:i';

    public function comment_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'comment_user_id');
    }
}
