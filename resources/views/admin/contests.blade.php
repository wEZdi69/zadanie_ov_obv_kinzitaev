@extends('layouts.app')

@section('title', 'Управление конкурсами')

@section('content')
    <div class="card">
        <h1>Управление конкурсами</h1>
        <a href="{{ route('dashboard') }}" class="btn">← Назад</a>
    </div>

    <div class="card">
        <h2>Создать новый конкурс</h2>
        <form action="{{ route('admin.contests.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Название конкурса</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Дедлайн</label>
                <input type="datetime-local" name="deadline_at" required>
            </div>
            <button type="submit" class="btn btn-success">Создать конкурс</button>
        </form>
    </div>

    <div class="card">
        <h2>Существующие конкурсы</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 1rem; text-align: left;">ID</th>
                    <th style="padding: 1rem; text-align: left;">Название</th>
                    <th style="padding: 1rem; text-align: left;">Дедлайн</th>
                    <th style="padding: 1rem; text-align: left;">Статус</th>
                    <th style="padding: 1rem; text-align: left;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contests as $contest)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 1rem;">{{ $contest->id }}</td>
                        <td style="padding: 1rem;">{{ $contest->title }}</td>
                        <td style="padding: 1rem;">{{ $contest->deadline_at->format('d.m.Y H:i') }}</td>
                        <td style="padding: 1rem;">
                            <span class="status-badge" style="background: {{ $contest->is_active ? '#28a745' : '#dc3545' }}; color: white;">
                                {{ $contest->is_active ? 'Активен' : 'Неактивен' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.contests.update', $contest) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="{{ !$contest->is_active }}">
                                <button type="submit" class="btn" style="padding: 0.25rem 0.5rem;">
                                    {{ $contest->is_active ? 'Деактивировать' : 'Активировать' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection