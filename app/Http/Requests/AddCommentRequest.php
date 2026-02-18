<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
    public function authorize()
    {
        $submission = $this->route('submission');
        
        if ($this->user()->isParticipant()) {
            return $submission->user_id === $this->user()->id;
        }
        
        return $this->user()->isJury() || $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'body' => 'required|string|max:1000',
        ];
    }
}