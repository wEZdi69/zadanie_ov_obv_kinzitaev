@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="card">
        <h1>Добро пожаловать на платформу конкурсов</h1>
    </div>
    
    <h2>Активные конкурсы</h2>
    
    @forelse($contests as $contest)
        <div class="card">
            <h3>{{ $contest->title }}</h3>
            <p>{{ $contest->description }}</p>
            <p><strong>Дедлайн:</strong> {{ $contest->deadline_at->format('d.m.Y H:i') }}</p>
        </div>
    @empty
        <div class="card">
            <p>Нет активных конкурсов</p>
        </div>
    @endforelse
@endsection