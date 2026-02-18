<?php

namespace App\Jobs;

use App\Models\Attachment;
use App\Services\AttachmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attachment;

    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function handle(AttachmentService $attachmentService): void
    {
        try {
            // Проверка имени файла
            if (strlen($this->attachment->original_name) > 255) {
                throw new \Exception('Filename is too long');
            }

            // Проверка на вредоносные символы в имени
            if (preg_match('/[^\w\s.-]/u', $this->attachment->original_name)) {
                throw new \Exception('Filename contains invalid characters');
            }

            // Проверка MIME типа
            if (!in_array($this->attachment->mime, Attachment::ALLOWED_MIMES)) {
                throw new \Exception('Invalid file type');
            }

            // Проверка размера
            if ($this->attachment->size > Attachment::MAX_SIZE) {
                throw new \Exception('File size exceeds limit');
            }

            // Если все проверки пройдены
            $attachmentService->markScanned($this->attachment);

        } catch (\Exception $e) {
            $attachmentService->reject($this->attachment, $e->getMessage());
        }
    }
}