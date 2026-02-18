<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Contest;
use App\Services\SubmissionService;
use App\Services\AttachmentService;
use Illuminate\Http\Request;

class SubmissionWebController extends Controller
{
    protected $submissionService;
    protected $attachmentService;
    
    public function __construct(SubmissionService $submissionService, AttachmentService $attachmentService)
    {
        $this->submissionService = $submissionService;
        $this->attachmentService = $attachmentService;
        $this->middleware('auth');
    }
    
    public function create()
    {
        $contests = Contest::where('is_active', true)
            ->where('deadline_at', '>', now())
            ->get();
        
        return view('participant.create', ['contests' => $contests]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'contest_id' => 'required|exists:contests,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        try {
            $submission = $this->submissionService->create(
                $request->all(),
                auth()->user()
            );
            
            return redirect()->route('submission.show', $submission)
                ->with('success', 'Черновик создан');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function show(Submission $submission)
    {
        $this->authorize('view', $submission);
        
        $submission->load(['contest', 'attachments', 'comments.user']);
        
        if (auth()->user()->isParticipant()) {
            return view('participant.show', ['submission' => $submission]);
        } elseif (auth()->user()->isJury()) {
            return view('jury.show', ['submission' => $submission]);
        } else {
            return view('admin.show', ['submission' => $submission]);
        }
    }
    
    public function edit(Submission $submission)
    {
        $this->authorize('update', $submission);
        
        if (!$submission->canEdit()) {
            return redirect()->route('submission.show', $submission)
                ->with('error', 'Нельзя редактировать заявку в текущем статусе');
        }
        
        return view('participant.edit', ['submission' => $submission]);
    }
    
    public function update(Request $request, Submission $submission)
    {
        $this->authorize('update', $submission);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        try {
            $this->submissionService->update($submission, $request->all());
            return redirect()->route('submission.show', $submission)
                ->with('success', 'Заявка обновлена');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function submit(Submission $submission)
    {
        $this->authorize('update', $submission);
        
        try {
            $this->submissionService->submit($submission);
            return redirect()->route('submission.show', $submission)
                ->with('success', 'Заявка отправлена на проверку');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function changeStatus(Request $request, Submission $submission)
    {
        $this->authorize('changeStatus', $submission);
        
        $request->validate([
            'status' => 'required|in:submitted,needs_fix,accepted,rejected'
        ]);
        
        try {
            $this->submissionService->changeStatus($submission, $request->status);
            return redirect()->route('submission.show', $submission)
                ->with('success', 'Статус изменен на ' . $request->status);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function addComment(Request $request, Submission $submission)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);
        
        try {
            $this->submissionService->addComment(
                $submission,
                $request->body,
                auth()->user()
            );
            
            return redirect()->route('submission.show', $submission)
                ->with('success', 'Комментарий добавлен');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}