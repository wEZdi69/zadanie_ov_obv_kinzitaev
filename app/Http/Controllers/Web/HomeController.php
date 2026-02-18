<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $contests = Contest::where('is_active', true)
            ->where('deadline_at', '>', now())
            ->get();
        
        return view('home', ['contests' => $contests]);
    }
    
    public function login($role)
    {
        $user = \App\Models\User::where('role', $role)->first();
        if ($user) {
            auth()->login($user);
            return redirect()->route('dashboard')->with('success', 'Вы вошли как ' . $role);
        }
        return redirect('/')->with('error', 'Пользователь не найден');
    }
    
    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'Вы вышли из системы');
    }
}