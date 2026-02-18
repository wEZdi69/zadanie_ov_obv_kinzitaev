@extends('layouts.app')

@section('title', 'Управление пользователями')

@section('content')
    <div class="card">
        <h1>Управление пользователями</h1>
        <a href="{{ route('dashboard') }}" class="btn">← Назад</a>
    </div>

    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 1rem; text-align: left;">ID</th>
                    <th style="padding: 1rem; text-align: left;">Имя</th>
                    <th style="padding: 1rem; text-align: left;">Email</th>
                    <th style="padding: 1rem; text-align: left;">Роль</th>
                    <th style="padding: 1rem; text-align: left;">Заявок</th>
                    <th style="padding: 1rem; text-align: left;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 1rem;">{{ $user->id }}</td>
                        <td style="padding: 1rem;">{{ $user->name }}</td>
                        <td style="padding: 1rem;">{{ $user->email }}</td>
                        <td style="padding: 1rem;">
                            <span class="status-badge" style="background: 
                                @if($user->role == 'admin') #dc3545
                                @elseif($user->role == 'jury') #28a745
                                @else #007bff
                                @endif; color: white;">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">{{ $user->submissions_count ?? 0 }}</td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" style="display: flex; gap: 0.5rem;">
                                @csrf
                                @method('PUT')
                                <select name="role">
                                    <option value="participant" {{ $user->role == 'participant' ? 'selected' : '' }}>Участник</option>
                                    <option value="jury" {{ $user->role == 'jury' ? 'selected' : '' }}>Жюри</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Админ</option>
                                </select>
                                <button type="submit" class="btn" style="padding: 0.25rem 0.5rem;">Изменить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection