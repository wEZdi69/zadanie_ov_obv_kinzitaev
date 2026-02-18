@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="card" style="max-width: 400px; margin: 0 auto;">
    <h1>Вход в систему</h1>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" required>
        </div>
        
        @if($errors->any())
            <div style="color: #dc3545; margin-bottom: 1rem;">
                {{ $errors->first() }}
            </div>
        @endif
        
        <button type="submit" class="btn btn-success">Войти</button>
    </form>
</div>
@endsection