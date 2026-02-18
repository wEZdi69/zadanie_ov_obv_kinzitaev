<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attachment;

class UploadAttachmentRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:pdf,zip,png,jpg',
                'max:' . (Attachment::MAX_SIZE / 1024),
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'Размер файла не должен превышать 10MB',
            'file.mimes' => 'Допустимые типы файлов: pdf, zip, png, jpg',
        ];
    }
}