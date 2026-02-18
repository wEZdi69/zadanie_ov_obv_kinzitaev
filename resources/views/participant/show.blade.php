@extends('layouts.app')

@section('title', $submission->title)

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <h1>{{ $submission->title }}</h1>
            <span class="status-badge status-{{ $submission->status }}">
                {{ $submission->status }}
            </span>
        </div>
        
        <p><strong>Конкурс:</strong> {{ $submission->contest->title }}</p>
        <p><strong>Описание:</strong> {{ $submission->description }}</p>
        <p><strong>Создано:</strong> {{ $submission->created_at->format('d.m.Y H:i') }}</p>
        
        <div style="margin: 2rem 0;">
            <h2>Файлы</h2>
            
            @if($submission->canEdit() && $submission->attachments->count() < 3)
                <form action="{{ route('attachment.upload', $submission) }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 1rem;">
                    @csrf
                    <input type="file" name="file" required>
                    <button type="submit" class="btn">Загрузить файл</button>
                    <small>Максимум 10MB, типы: pdf, zip, png, jpg</small>
                </form>
            @endif
            
            @forelse($submission->attachments as $attachment)
                <div style="margin: 0.5rem 0; padding: 0.5rem; background: #f8f9fa; border-radius: 4px;">
                    <strong>{{ $attachment->original_name }}</strong>
                    <div>
                        Размер: {{ round($attachment->size / 1024, 2) }} KB
                        Статус: <span class="status-badge status-{{ $attachment->status }}">{{ $attachment->status }}</span>
                        @if($attachment->rejection_reason)
                            <p style="color: #dc3545;">Причина: {{ $attachment->rejection_reason }}</p>
                        @endif
                        @if($attachment->isScanned())
                            <a href="{{ route('attachment.download', $attachment) }}" class="btn" style="margin-top: 0.5rem;">Скачать</a>
                        @endif
                    </div>
                </div>
            @empty
                <p>Файлы не загружены</p>
            @endforelse
        </div>
        
        <div style="margin: 2rem 0;">
            <h2>Комментарии</h2>
            
            <form action="{{ route('submission.comment', $submission) }}" method="POST" style="margin-bottom: 1rem;">
                @csrf
                <div class="form-group">
                    <textarea name="body" rows="3" placeholder="Ваш комментарий..." required></textarea>
                </div>
                <button type="submit" class="btn">Добавить комментарий</button>
            </form>
            
            @forelse($submission->comments as $comment)
                <div style="margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                    <strong>{{ $comment->user->name }} ({{ $comment->user->role }})</strong>
                    <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                    <p style="margin-top: 0.5rem;">{{ $comment->body }}</p>
                </div>
            @empty
                <p>Комментариев нет</p>
            @endforelse
        </div>
        
        <div style="margin-top: 2rem;">
            @if($submission->canEdit())
                <a href="{{ route('submission.edit', $submission) }}" class="btn btn-warning">Редактировать</a>
            @endif
            
            @if($submission->status == 'draft' && $submission->hasScannedAttachments())
                <form action="{{ route('submission.submit', $submission) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Отправить на проверку</button>
                </form>
            @endif
            
            <a href="{{ route('dashboard') }}" class="btn">Назад</a>
        </div>
    </div>
@endsection