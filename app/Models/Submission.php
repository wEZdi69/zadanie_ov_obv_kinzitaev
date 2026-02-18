<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'contest_id',
        'user_id',
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_NEEDS_FIX = 'needs_fix';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    public static function getAllowedTransitions(): array
    {
        return [
            self::STATUS_DRAFT => [self::STATUS_SUBMITTED],
            self::STATUS_SUBMITTED => [self::STATUS_NEEDS_FIX, self::STATUS_ACCEPTED, self::STATUS_REJECTED],
            self::STATUS_NEEDS_FIX => [self::STATUS_SUBMITTED],
            self::STATUS_ACCEPTED => [],
            self::STATUS_REJECTED => [],
        ];
    }

    public function canEdit(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_NEEDS_FIX]);
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(SubmissionComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function hasScannedAttachments(): bool
    {
        return $this->attachments()
            ->where('status', 'scanned')
            ->exists();
    }

    public function getAttachmentsCount(): int
    {
        return $this->attachments()->count();
    }
}