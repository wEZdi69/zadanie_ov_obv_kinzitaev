<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\AddCommentRequest;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Submission::with(['contest', 'user', 'attachments', 'comments.user']);
        
        if ($user->isParticipant()) {
            $query->where('user_id', $user->id);
        }
        
        $submissions = $query->latest()->paginate(15);
        
        return response()->json($submissions);
    }

    public function show(Submission $submission): JsonResponse
    {
        $user = request()->user();
        
        if ($user->isParticipant() && $submission->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $submission->load(['contest', 'user', 'attachments', 'comments.user']);
        
        return response()->json($submission);
    }

    public function store(StoreSubmissionRequest $request): JsonResponse
    {
        $submission = $this->submissionService->create(
            $request->validated(),
            $request->user()
        );
        
        return response()->json($submission, 201);
    }

    public function update(UpdateSubmissionRequest $request, Submission $submission): JsonResponse
    {
        $submission = $this->submissionService->update(
            $submission,
            $request->validated()
        );
        
        return response()->json($submission);
    }

    public function submit(Submission $submission): JsonResponse
    {
        try {
            $submission = $this->submissionService->submit($submission);
            return response()->json(['message' => 'Submission submitted successfully', 'submission' => $submission]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function changeStatus(ChangeStatusRequest $request, Submission $submission): JsonResponse
    {
        try {
            $submission = $this->submissionService->changeStatus(
                $submission,
                $request->status
            );
            return response()->json(['message' => 'Status changed successfully', 'submission' => $submission]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function addComment(AddCommentRequest $request, Submission $submission): JsonResponse
    {
        $comment = $this->submissionService->addComment(
            $submission,
            $request->body,
            $request->user()
        );
        
        return response()->json($comment, 201);
    }
}