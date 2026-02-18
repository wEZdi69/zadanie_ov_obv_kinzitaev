<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }
    
    public function contests()
    {
        $contests = Contest::orderBy('created_at', 'desc')->get();
        return view('admin.contests', ['contests' => $contests]);
    }
    
    public function createContest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline_at' => 'required|date|after:today',
        ]);
        
        Contest::create($request->all());
        
        return redirect()->route('admin.contests')
            ->with('success', 'Конкурс создан');
    }
    
    public function updateContest(Request $request, Contest $contest)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline_at' => 'required|date',
            'is_active' => 'boolean',
        ]);
        
        $contest->update($request->all());
        
        return redirect()->route('admin.contests')
            ->with('success', 'Конкурс обновлен');
    }
    
    public function users()
    {
        $users = User::withCount('submissions')->get();
        return view('admin.users', ['users' => $users]);
    }
    
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,jury,participant',
        ]);
        
        $user->role = $request->role;
        $user->save();
        
        return redirect()->route('admin.users')
            ->with('success', 'Роль пользователя обновлена');
    }
}