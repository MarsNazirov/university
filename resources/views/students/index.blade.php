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
                <td>{{ $student->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>