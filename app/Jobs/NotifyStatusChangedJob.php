<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyStatusChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $submission;
    protected $oldStatus;

    public function __construct(Submission $submission, string $oldStatus)
    {
        $this->submission = $submission;
        $this->oldStatus = $oldStatus;
    }

    public function handle(): void
    {
        $message = sprintf(
            'Статус вашей работы "%s" изменен с "%s" на "%s"',
            $this->submission->title,
            $this->oldStatus,
            $this->submission->status
        );

        // Создание уведомления в БД
        Notification::create([
            'user_id' => $this->submission->user_id,
            'type' => 'status_changed',
            'message' => $message,
            'data' => [
                'submission_id' => $this->submission->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->submission->status,
            ],
        ]);

        // Логирование
        Log::info('Submission status changed', [
            'submission_id' => $this->submission->id,
            'user_id' => $this->submission->user_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->submission->status,
        ]);

        // Здесь можно добавить отправку email
        // Mail::to($this->submission->user->email)->send(...);
    }
}