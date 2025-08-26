<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome – Laravel 12 App</title>
    <!-- Bootstrap CSS via CDN (you can bundle locally if preferred) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">Laravel 12</a>
        <span class="navbar-text text-muted">
                A Modern PHP Framework
            </span>
    </div>
</nav>
<main class="flex-fill d-flex align-items-center justify-content-center">
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold mb-3 text-dark">Welcome to Your Laravel 12 Application</h1>
        <p class="lead text-secondary mb-4">
            You're running Laravel 12 with a custom structure and modern tooling.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="https://laravel.com/docs/12.x" target="_blank" class="btn btn-primary btn-lg">
                Documentation
            </a>
            <a href="{{ url('/health') }}" class="btn btn-outline-secondary btn-lg">
                Health Check
            </a>
        </div>
    </div>
</main>
<footer class="text-center text-muted py-3 mt-auto">
    &copy; {{ date('Y') }} Laravel 12 Starter. Built with ❤️.
</footer>
<!-- Bootstrap JS via CDN (optional, for dynamic components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
