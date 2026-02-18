<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Конкурс платформа')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        nav { background: #333; color: white; padding: 1rem; }
        nav a { color: white; text-decoration: none; margin-right: 1rem; }
        nav a:hover { color: #ddd; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; 
                text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-danger { background: #dc3545; }
        .card { background: white; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; 
                box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; 
        }
        .status-badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 4px; 
                        font-size: 0.875rem; font-weight: bold; }
        .status-draft { background: #6c757d; color: white; }
        .status-submitted { background: #007bff; color: white; }
        .status-needs_fix { background: #ffc107; color: #333; }
        .status-accepted { background: #28a745; color: white; }
        .status-rejected { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <nav>
        <div class="container">
            <a href="/">Главная</a>
            @auth
                <a href="{{ route('dashboard') }}">Дашборд</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.contests') }}">Управление конкурсами</a>
                    <a href="{{ route('admin.users') }}">Пользователи</a>
                @endif
                <a href="{{ route('logout') }}" style="float: right;">Выйти ({{ auth()->user()->name }})</a>
            @else
                <a href="{{ route('login.form') }}" style="float: right;">Войти</a>
            @endauth
        </div>
    </nav>
    
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        @yield('content')
    </div>
</body>
</html>