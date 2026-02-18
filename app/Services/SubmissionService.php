<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Jobs\NotifyStatusChangedJob;

class SubmissionService
{
    public function create(array $data, User $user): Submission
    {
        return DB::transaction(function () use ($data, $user) {
            $submission = Submission::create([
                'contest_id' => $data['contest_id'],
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => Submission::STATUS_DRAFT,
            ]);

            return $submission;
        });
    }

    public function update(Submission $submission, array $data): Submission
    {
        if (!$submission->canEdit()) {
            throw new \Exception('Cannot edit submission in current status');
        }

        return DB::transaction(function () use ($submission, $data) {
            $submission->update($data);
            return $submission;
        });
    }

    public function submit(Submission $submission): Submission
    {
        if (!$submission->canEdit()) {
            throw new \Exception('Cannot submit submission in current status');
        }

        if (!$submission->hasScannedAttachments()) {
            throw new \Exception('Submission must have at least one scanned attachment');
        }

        return DB::transaction(function () use ($submission) {
            $submission->status = Submission::STATUS_SUBMITTED;
            $submission->save();

            NotifyStatusChangedJob::dispatch($submission, Submission::STATUS_SUBMITTED);

            return $submission;
        });
    }

    public function changeStatus(Submission $submission, string $newStatus): Submission
    {
        $allowedTransitions = Submission::getAllowedTransitions()[$submission->status] ?? [];
        
        if (!in_array($newStatus, $allowedTransitions)) {
            throw new \Exception('Invalid status transition');
        }

        return DB::transaction(function () use ($submission, $newStatus) {
            $oldStatus = $submission->status;
            $submission->status = $newStatus;
            $submission->save();

            NotifyStatusChangedJob::dispatch($submission, $oldStatus);

            return $submission;
        });
    }

    public function addComment(Submission $submission, string $body, User $user): SubmissionComment
    {
        return DB::transaction(function () use ($submission, $body, $user) {
            return SubmissionComment::create([
                'submission_id' => $submission->id,
                'user_id' => $user->id,
                'body' => $body,
            ]);
        });
    }
}