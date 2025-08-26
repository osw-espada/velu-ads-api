<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 | Server Error</title>
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
        .btn {
            display: inline-block;
            background-color: #3490dc;
            color: #fff;
            padding: 10px 28px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 18px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #2779bd;
        }
    </style>
</head>
<body>
<h1>500</h1>
<h2>Server Error</h2>
<p>Whoops, something went wrong on our servers.<br>
    Please try again later or return to the homepage.</p>
<a href="{{ route('web.home') }}" class="btn">Return Home</a>
</body>
</html>
