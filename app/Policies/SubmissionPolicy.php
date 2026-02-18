<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function view(User $user, Submission $submission)
    {
        return $user->isAdmin() || 
               $user->isJury() || 
               ($user->isParticipant() && $submission->user_id === $user->id);
    }
    
    public function create(User $user)
    {
        return $user->isParticipant();
    }
    
    public function update(User $user, Submission $submission)
    {
        return $user->isParticipant() && 
               $submission->user_id === $user->id && 
               $submission->canEdit();
    }
    
    public function changeStatus(User $user, Submission $submission)
    {
        return $user->isJury() || $user->isAdmin();
    }
    
    public function upload(User $user, Submission $submission)
    {
        return $user->isParticipant() && 
               $submission->user_id === $user->id && 
               $submission->canEdit();
    }
}