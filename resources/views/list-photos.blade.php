<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Bukti Bayar</title>
</head>
<body>
    <h2>Daftar Bukti Bayar untuk ID {{ $id }}</h2>
    <div>
        @foreach ($files as $file)
            <img src="{{ asset(str_replace('public/', 'storage/', $file)) }}" alt="Bukti Bayar" width="200">
        @endforeach
    </div>
</body>
</html>
