<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Submission;

class ChangeStatusRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isJury();
    }

    public function rules()
    {
        $submission = $this->route('submission');
        $allowedTransitions = Submission::getAllowedTransitions()[$submission->status] ?? [];

        return [
            'status' => 'required|in:' . implode(',', $allowedTransitions),
        ];
    }

    public function messages()
    {
        return [
            'status.in' => 'Недопустимый переход статуса',
        ];
    }
}