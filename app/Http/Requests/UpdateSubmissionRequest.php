<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubmissionRequest extends FormRequest
{
    public function authorize()
    {
        $submission = $this->route('submission');
        return $this->user()->isParticipant() && 
               $submission->user_id === $this->user()->id &&
               $submission->canEdit();
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ];
    }
}