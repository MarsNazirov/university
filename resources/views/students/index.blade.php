<!DOCTYPE html>
<html>
<head>
    <title>Список студентов</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Студенты в общежитии</h1>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            {{ session('error') }}
        </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ФИО</th>
                <th>Группа</th>
                <th>Курс</th>
                <th>Комната</th>
                <th>Этаж</th>
                <th>Статус студента</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->full_name }}</td>
                <td>{{ $student->group }}</td>
                <td>{{ $student->course }}</td>
                <td>
                    @if($student->room)
                        {{ $student->room->number }}
                    @else
                        Нет комнаты
                    @endif
                </td>
                <td>
                    @if($student->room)
                        {{ $student->room->floor }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($student->status === 'active')
                        Активный
                    @elseif ($student->status === 'expelled')
                        Отчислен
                    @elseif ($student->status === 'academic_leave')
                        Академ.отпуск
                    @else
                        {{ $student->status }}
                    @endif
                </td>
                <td>
                    @if ($student->room_id !== null)
                    <form action="{{ route('students.evict', $student->id) }}" method="POST">
                        @method('PATCH')
                        @CSRF
                        <button type="submit">Выселить</button>
                    </form>
                    @endif
                    {{-- <a href="{{ route('students.evict', $student->id) }}">Выселить</a> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>