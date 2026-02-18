@extends('layouts.app')

@section('title', 'Проверка работы: ' . $submission->title)

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <h1>{{ $submission->title }}</h1>
            <span class="status-badge status-{{ $submission->status }}">
                {{ $submission->status }}
            </span>
        </div>
        
        <p><strong>Участник:</strong> {{ $submission->user->name }} ({{ $submission->user->email }})</p>
        <p><strong>Конкурс:</strong> {{ $submission->contest->title }}</p>
        <p><strong>Описание:</strong> {{ $submission->description }}</p>
        
        <div style="margin: 2rem 0;">
            <h2>Файлы работы</h2>
            @foreach($submission->attachments as $attachment)
                <div style="margin: 0.5rem 0; padding: 0.5rem; background: #f8f9fa; border-radius: 4px;">
                    <strong>{{ $attachment->original_name }}</strong>
                    <div>
                        Размер: {{ round($attachment->size / 1024, 2) }} KB
                        Статус сканирования: 
                        <span class="status-badge status-{{ $attachment->status }}">
                            {{ $attachment->status }}
                        </span>
                        @if($attachment->isScanned())
                            <a href="{{ route('attachment.download', $attachment) }}" class="btn" style="margin-left: 1rem;">Скачать</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div style="margin: 2rem 0;">
            <h2>Изменить статус</h2>
            
            @if(in_array($submission->status, ['submitted', 'needs_fix']))
                <form action="{{ route('submission.status', $submission) }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 1rem;">
                        @if($submission->status == 'submitted')
                            <button type="submit" name="status" value="needs_fix" class="btn btn-warning">Запросить доработку</button>
                            <button type="submit" name="status" value="accepted" class="btn btn-success">Принять</button>
                            <button type="submit" name="status" value="rejected" class="btn btn-danger">Отклонить</button>
                        @elseif($submission->status == 'needs_fix')
                            <button type="submit" name="status" value="submitted" class="btn btn-primary">Отметить исправления</button>
                        @endif
                    </div>
                </form>
            @endif
        </div>
        
        <div style="margin: 2rem 0;">
            <h2>Комментарии</h2>
            
            <form action="{{ route('submission.comment', $submission) }}" method="POST" style="margin-bottom: 1rem;">
                @csrf
                <div class="form-group">
                    <textarea name="body" rows="3" placeholder="Ваш комментарий для участника..." required></textarea>
                </div>
                <button type="submit" class="btn">Добавить комментарий</button>
            </form>
            
            @foreach($submission->comments as $comment)
                <div style="margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                    <strong>{{ $comment->user->name }} ({{ $comment->user->role }})</strong>
                    <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                    <p style="margin-top: 0.5rem;">{{ $comment->body }}</p>
                </div>
            @endforeach
        </div>
        
        <a href="{{ route('dashboard') }}" class="btn">Назад к списку</a>
    </div>
@endsection