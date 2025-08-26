<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 | Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 { font-size: 5rem; margin: 0; color: #e53e3e; }
        h2 { font-size: 2rem; margin: 0; }
        p { margin: 20px 0; font-size: 1.1rem; }
        a { color: #3490dc; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<h1>404</h1>
<h2>Page Not Found</h2>
<p>The page you are looking for doesn't exist or has been moved.</p>
<a href="{{ url('/') }}">Go back home</a>
</body>
</html>
