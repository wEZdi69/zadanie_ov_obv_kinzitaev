@extends('layouts.app')

@section('title', 'Редактирование заявки')

@section('content')
    <div class="card">
        <h1>Редактирование заявки</h1>
        
        <form action="{{ route('submission.update', $submission) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Название работы</label>
                <input type="text" name="title" value="{{ old('title', $submission->title) }}" required>
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="5" required>{{ old('description', $submission->description) }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-success">Сохранить изменения</button>
            <a href="{{ route('submission.show', $submission) }}" class="btn">Отмена</a>
        </form>
    </div>
@endsection