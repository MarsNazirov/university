<!DOCTYPE html>
<html>
<head>
    <title>Заселение в комнату {{ $room->number }}</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; }
        select, button { width: 100%; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Заселение в комнату {{ $room->number }}</h1>
    <p>Этаж: {{ $room->floor }}, мест: {{ $room->beds_count }}</p>
    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @method('PATCH')
        @csrf
        <select name="student_id" required>
            <option value="">Выберите студента</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}">
                    {{ $student->full_name }} ({{ $student->group }})
                    @if($student->room_id)
                        - сейчас в комнате {{ $student->room->number }}
                    @endif
                </option>
            @endforeach
        </select>
        
        <button type="submit">Заселить</button>
    </form>
    
    <p><a href="/rooms">← Назад к комнатам</a></p>
</body>
</html>