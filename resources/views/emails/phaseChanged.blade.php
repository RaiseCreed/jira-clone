<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status of your ticket has been updated!</title>
</head>
<body>
    <h1>Welcome!</h1>
    <p>Your ticket: {{$ticket->title}} has new status -> {{$ticket->status->name}}</p>
</body>
</html>