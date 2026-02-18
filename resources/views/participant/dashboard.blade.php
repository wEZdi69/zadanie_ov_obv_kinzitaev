@extends('layouts.app')

@section('title', 'Кабинет участника')

@section('content')
    <div class="card">
        <h1>Кабинет участника</h1>
        <p>Добро пожаловать, {{ auth()->user()->name }}!</p>
        <a href="{{ route('submission.create') }}" class="btn btn-success">+ Создать новую заявку</a>
    </div>
    
    <h2>Мои заявки</h2>
    
    @forelse($submissions as $submission)
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h3>{{ $submission->title }}</h3>
                    <p>Конкурс: {{ $submission->contest->title }}</p>
                    <p>{{ $submission->description }}</p>
                    <p>Создано: {{ $submission->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <span class="status-badge status-{{ $submission->status }}">
                        {{ $submission->status }}
                    </span>
                </div>
            </div>
            
            <div style="margin-top: 1rem;">
                <strong>Файлы ({{ $submission->attachments->count() }}/3):</strong>
                @foreach($submission->attachments as $attachment)
                    <div style="margin: 0.5rem 0; padding: 0.5rem; background: #f8f9fa; border-radius: 4px;">
                        {{ $attachment->original_name }} 
                        ({{ round($attachment->size / 1024, 2) }} KB)
                        <span class="status-badge status-{{ $attachment->status }}">
                            {{ $attachment->status }}
                        </span>
                        @if($attachment->isScanned())
                            <a href="{{ route('attachment.download', $attachment) }}" class="btn" style="padding: 0.25rem 0.5rem;">Скачать</a>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div style="margin-top: 1rem;">
                <a href="{{ route('submission.show', $submission) }}" class="btn">Просмотр</a>
                
                @if($submission->canEdit())
                    <a href="{{ route('submission.edit', $submission) }}" class="btn btn-warning">Редактировать</a>
                @endif
                
                @if($submission->status == 'draft' && $submission->hasScannedAttachments())
                    <form action="{{ route('submission.submit', $submission) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Отправить на проверку</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <p>У вас пока нет заявок</p>
            <a href="{{ route('submission.create') }}" class="btn">Создать первую заявку</a>
        </div>
    @endforelse
@endsection