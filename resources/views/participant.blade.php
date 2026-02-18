<!DOCTYPE html>
<html>
<head>
    <title>Кабинет участника</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .submission { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        .status { font-weight: bold; color: #4CAF50; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Кабинет участника</h1>
    <p>Вы вошли как: {{ auth()->user()->name }}</p>
    
    <h2>Создать новую заявку</h2>
    <form method="POST" action="/submissions">
        @csrf
        <input type="text" name="title" placeholder="Название работы" required>
        <textarea name="description" placeholder="Описание" required></textarea>
        <select name="contest_id">
            @foreach(App\Models\Contest::all() as $contest)
                <option value="{{ $contest->id }}">{{ $contest->title }}</option>
            @endforeach
        </select>
        <button type="submit">Создать черновик</button>
    </form>
    
    <h2>Мои заявки</h2>
    @foreach($submissions as $submission)
        <div class="submission">
            <h3>{{ $submission->title }}</h3>
            <p>{{ $submission->description }}</p>
            <p class="status">Статус: {{ $submission->status }}</p>
            
            @if(in_array($submission->status, ['draft', 'needs_fix']))
                <form method="POST" action="/submissions/{{ $submission->id }}/attachments" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" required>
                    <button type="submit">Загрузить файл</button>
                </form>
            @endif
            
            @if($submission->status == 'draft' && $submission->attachments->count() > 0)
                <form method="POST" action="/submissions/{{ $submission->id }}/submit">
                    @csrf
                    <button type="submit">Отправить на проверку</button>
                </form>
            @endif
        </div>
    @endforeach
</body>
</html>