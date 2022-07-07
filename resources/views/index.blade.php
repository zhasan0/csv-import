<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Import</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
<h1></h1>
<form action="{{ route('csv.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" id="csv_file" name="file">
    <button type="submit">Submit</button>
</form>
<br>
<p>{{ session('success') }}</p>

{{--<h1>List</h1>--}}
{{--<table>--}}
{{--    <tr>--}}
{{--        <td></td>--}}
{{--        <td></td>--}}
{{--        <td></td>--}}
{{--        <td></td>--}}
{{--        <td></td>--}}
{{--    </tr>--}}
{{--</table>--}}
</body>
</html>
