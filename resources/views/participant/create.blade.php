@extends('layouts.app')

@section('title', 'Создание заявки')

@section('content')
    <div class="card">
        <h1>Создание новой заявки</h1>
        
        <form action="{{ route('submission.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Конкурс</label>
                <select name="contest_id" required>
                    <option value="">Выберите конкурс</option>
                    @foreach($contests as $contest)
                        <option value="{{ $contest->id }}">{{ $contest->title }} (до {{ $contest->deadline_at->format('d.m.Y') }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Название работы</label>
                <input type="text" name="title" required value="{{ old('title') }}">
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="5" required>{{ old('description') }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-success">Создать черновик</button>
            <a href="{{ route('dashboard') }}" class="btn">Отмена</a>
        </form>
    </div>
@endsection