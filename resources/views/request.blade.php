<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Bot Request</title>
</head>
<body>
    <h1>Telegram Bot Request Data</h1>
    <ul>
        @foreach ($data as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</li>
        @endforeach
    </ul>
</body>
</html>
