<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\ScanAttachmentJob;

class AttachmentService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('s3');
    }

    public function upload(Submission $submission, UploadedFile $file, User $user): Attachment
    {
        // Проверка лимита файлов
        if ($submission->getAttachmentsCount() >= Attachment::MAX_FILES_PER_SUBMISSION) {
            throw new \Exception('Maximum number of files reached');
        }

        // Генерация уникального ключа для S3
        $key = sprintf(
            'submissions/%d/%s-%s',
            $submission->id,
            Str::uuid(),
            $file->getClientOriginalName()
        );
        
        \Log::info('Uploading file to S3', [
            'key' => $key,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType()
        ]);

        try {
            // Загрузка в S3
            $path = $this->disk->put($key, fopen($file->getRealPath(), 'r+'), 'private');
            
            \Log::info('Upload result', ['path' => $path]);
            
            if (!$path) {
                throw new \Exception('Failed to upload file - disk put returned false');
            }
        } catch (\Exception $e) {
            \Log::error('S3 upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to upload file: ' . $e->getMessage());
        }

        // Создание записи в БД
        $attachment = Attachment::create([
            'submission_id' => $submission->id,
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'storage_key' => $key,
            'status' => Attachment::STATUS_PENDING,
        ]);

        // Запуск задачи сканирования
        ScanAttachmentJob::dispatch($attachment);

        return $attachment;
    }

    public function markScanned(Attachment $attachment): Attachment
    {
        $attachment->status = Attachment::STATUS_SCANNED;
        $attachment->save();
        
        return $attachment;
    }

    public function reject(Attachment $attachment, string $reason): Attachment
    {
        $attachment->status = Attachment::STATUS_REJECTED;
        $attachment->rejection_reason = $reason;
        $attachment->save();
        
        return $attachment;
    }

    public function getSignedUrl(Attachment $attachment): string
    {
        // Проверка прав доступа
        if (!auth()->user()->isJury() && !auth()->user()->isAdmin() && 
            auth()->id() !== $attachment->submission->user_id) {
            throw new \Exception('Unauthorized');
        }

        // Генерация временной ссылки (5 минут)
        return $this->disk->temporaryUrl(
            $attachment->storage_key,
            now()->addMinutes(5)
        );
    }
}