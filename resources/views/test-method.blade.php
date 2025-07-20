<!DOCTYPE html>
<html>
<head>
    <title>Test Method Field</title>
</head>
<body>
    <h1>Test</h1>
    
    <form method="POST">
        @csrf
        @method('PATCH')
        <button type="submit">Test</button>
    </form>
    
    <script>
        console.log('Test JavaScript');
    </script>
</body>
</html>
