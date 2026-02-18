@extends('layouts.app')

@section('title', 'Панель жюри')

@section('content')
    <div class="card">
        <h1>Панель жюри</h1>
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1rem;">
            <div style="padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                <h3>Всего работ</h3>
                <p style="font-size: 2rem;">{{ $stats['total'] }}</p>
            </div>
            <div style="padding: 1rem; background: #fff3cd; border-radius: 8px;">
                <h3>На проверке</h3>
                <p style="font-size: 2rem;">{{ $stats['submitted'] }}</p>
            </div>
            <div style="padding: 1rem; background: #d4edda; border-radius: 8px;">
                <h3>Принято</h3>
                <p style="font-size: 2rem;">{{ $stats['accepted'] }}</p>
            </div>
            <div style="padding: 1rem; background: #f8d7da; border-radius: 8px;">
                <h3>Отклонено</h3>
                <p style="font-size: 2rem;">{{ $stats['rejected'] }}</p>
            </div>
        </div>
    </div>
    
    <h2>Все заявки</h2>
    
    @foreach($submissions as $submission)
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h3>{{ $submission->title }}</h3>
                    <p>Участник: {{ $submission->user->name }}</p>
                    <p>Конкурс: {{ $submission->contest->title }}</p>
                    <p>Описание: {{ $submission->description }}</p>
                </div>
                <div>
                    <span class="status-badge status-{{ $submission->status }}">
                        {{ $submission->status }}
                    </span>
                </div>
            </div>
            
            <div style="margin-top: 1rem;">
                <a href="{{ route('submission.show', $submission) }}" class="btn">Просмотреть и оценить</a>
            </div>
        </div>
    @endforeach
@endsection