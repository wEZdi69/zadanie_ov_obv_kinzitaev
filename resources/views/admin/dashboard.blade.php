@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
    <div class="card">
        <h1>Панель администратора</h1>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1rem;">
            <div style="padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                <h3>Конкурсы</h3>
                <p style="font-size: 2rem;">{{ $contests->count() }}</p>
            </div>
            <div style="padding: 1rem; background: #d4edda; border-radius: 8px;">
                <h3>Пользователи</h3>
                <p style="font-size: 2rem;">{{ $users->count() }}</p>
            </div>
            <div style="padding: 1rem; background: #fff3cd; border-radius: 8px;">
                <h3>Заявки</h3>
                <p style="font-size: 2rem;">{{ $submissions_count }}</p>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="{{ route('admin.contests') }}" class="btn">Управление конкурсами</a>
            <a href="{{ route('admin.users') }}" class="btn">Управление пользователями</a>
        </div>
    </div>
@endsection