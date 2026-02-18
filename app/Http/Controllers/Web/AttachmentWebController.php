<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Attachment;
use App\Services\AttachmentService;
use Illuminate\Http\Request;

class AttachmentWebController extends Controller
{
    protected $attachmentService;
    
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
        $this->middleware('auth');
    }
    
    public function upload(Request $request, Submission $submission)
    {
        $this->authorize('upload', $submission);
        
        $request->validate([
            'file' => 'required|file|mimes:pdf,zip,png,jpg|max:10240',
        ]);
        
        try {
            $this->attachmentService->upload(
                $submission,
                $request->file('file'),
                auth()->user()
            );
            
            return redirect()->route('submission.show', $submission)
                ->with('success', 'Файл загружен и отправлен на сканирование');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function download(Attachment $attachment)
    {
        try {
            $url = $this->attachmentService->getSignedUrl($attachment);
            return redirect($url);
        } catch (\Exception $e) {
            return back()->with('error', 'Нет доступа к файлу');
        }
    }
}