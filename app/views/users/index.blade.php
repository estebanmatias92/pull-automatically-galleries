<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pull Automatically Galleries - Home</title>
</head>
<body>
    <h1>Pull Automatically Galleries</h1>
    <div class="container">
        <h2>Users:</h2>
        <br>
        @foreach ($users as $user)
            <p>Name: {{$user->name}}</p>
        @endforeach
    </div>
</body>
</html>