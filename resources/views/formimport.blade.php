<!-- resources/views/import.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Import Members</title>
</head>
<body>

<form action="{{ url('import-members') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".xlsx,.xls">
    <button type="submit">Import</button>
</form>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

</body>
</html>
