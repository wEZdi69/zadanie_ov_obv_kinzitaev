<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'user_id',
        'original_name',
        'mime',
        'size',
        'storage_key',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SCANNED = 'scanned';
    const STATUS_REJECTED = 'rejected';

    const ALLOWED_MIMES = ['application/pdf', 'application/zip', 'image/png', 'image/jpeg'];
    const MAX_SIZE = 10485760; // 10MB
    const MAX_FILES_PER_SUBMISSION = 3;

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isScanned(): bool
    {
        return $this->status === self::STATUS_SCANNED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}