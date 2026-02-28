<!DOCTYPE html>
<html>
<head>
    <title>Комнаты общежития</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .occupied { background-color: #ffe6e6; }
        .available { background-color: #e6ffe6; }
    </style>
</head>
<body>
    <h1>Комнаты общежития</h1>
    
    <table>
        <thead>
            <tr>
                <th>Комната</th>
                <th>Этаж</th>
                <th>Тип</th>
                <th>Цена</th>
                <th>Всего мест</th>
                <th>Занято</th>
                <th>Свободно</th>
                <th>Статус</th>
                <th>Жильцы</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            @php
                $occupiedCount = $room->students->count();
                $freeBeds = $room->beds_count - $occupiedCount;
                $rowClass = $room->status == 'available' ? 'available' : 'occupied';
            @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $room->number }}</td>
                <td>{{ $room->floor }}</td>
                <td>{{ $room->type }}</td>
                <td>{{ $room->price }} ₽</td>
                <td>{{ $room->beds_count }}</td>
                <td>{{ $occupiedCount }}</td>
                <td>{{ $freeBeds }}</td>
                <td>{{ $room->status }}</td>
                <td>
                    @foreach($room->students as $student)
                        {{ $student->full_name }}<br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <p>
        <a href="/students">← К списку студентов</a>
    </p>
</body>
</html>