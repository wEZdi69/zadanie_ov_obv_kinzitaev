<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isParticipant()) {
            return $this->participantDashboard();
        } elseif ($user->isJury()) {
            return $this->juryDashboard();
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        
        return redirect('/');
    }
    
    private function participantDashboard()
    {
        $submissions = Submission::with(['contest', 'attachments', 'comments.user'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        $contests = Contest::where('is_active', true)
            ->where('deadline_at', '>', now())
            ->get();
        
        return view('participant.dashboard', [
            'submissions' => $submissions,
            'contests' => $contests
        ]);
    }
    
    private function juryDashboard()
    {
        $submissions = Submission::with(['user', 'contest', 'attachments', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $stats = [
            'total' => $submissions->count(),
            'submitted' => $submissions->where('status', 'submitted')->count(),
            'accepted' => $submissions->where('status', 'accepted')->count(),
            'rejected' => $submissions->where('status', 'rejected')->count(),
        ];
        
        return view('jury.dashboard', [
            'submissions' => $submissions,
            'stats' => $stats
        ]);
    }
    
    private function adminDashboard()
    {
        $contests = Contest::orderBy('created_at', 'desc')->get();
        $users = User::all();
        $submissions = Submission::count();
        
        return view('admin.dashboard', [
            'contests' => $contests,
            'users' => $users,
            'submissions_count' => $submissions
        ]);
    }
}