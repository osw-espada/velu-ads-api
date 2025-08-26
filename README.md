# Your Project Name

A modern Laravel application built with Laravel 12 (PHP 8.2+). This README covers local setup, development workflow, testing, quality tools, optional Docker usage, and deployment notes.

## Tech stack

- Backend: Laravel 12, PHP 8.2+
- Database: MySQL (or compatible)
- Frontend bundler: Vite (if using frontend assets)
- Notable packages:
    - Authentication: php-open-source-saver/jwt-auth
    - Payments: stripe/stripe-php
    - Phone validation: propaganistas/laravel-phone
    - Transformers: spatie/laravel-fractal
- Dev tooling: Pest, Mockery, Laravel Pint, Laravel Pail, Laravel Sail (Docker)

## Requirements

- PHP 8.2+ with required PHP extensions for Laravel
- Composer 2.x
- MySQL 8.x (or compatible)
- Postgres
- Node.js 18+ and npm 9+ (if building frontend assets)
- Git

## Getting started (local)

1) Clone the repository
- bash
  git clone git@github.com:osw-espada/velu-ads-api.git
  cd velu-ads-api

2) Environment
- Copy the example env file and configure values:
- bash
  cp .env.example .env
- Update at least:
    - APP_NAME=Your Project Name
    - APP_URL=http://localhost
    - DB_DATABASE=your_database_name
    - DB_USERNAME=your_db_username
    - DB_PASSWORD=your_db_password
- Generate the app key:
- bash
  php artisan key:generate

3) Install dependencies
- bash
  composer install

4) Database
- Run migrations (add --seed if you have seeders):
- bash
  php artisan migrate
- If your app serves files from storage:
- bash
  php artisan storage:link

5) Frontend assets (optional, if applicable)
- bash
  npm install
  npm run dev
- For production builds:
- bash
  npm run build

6) Run the application
- bash
  php artisan serve
- App will be available at http://127.0.0.1:8000 (unless configured otherwise).

## Optional: Docker with Laravel Sail

Use Docker if you prefer containerized development.

- bash
  cp .env.example .env
  composer install
  php artisan key:generate
  php artisan sail:install
  ./vendor/bin/sail up -d
  ./vendor/bin/sail artisan migrate
  ./vendor/bin/sail npm install
  ./vendor/bin/sail npm run dev

Stop containers:
- bash
  ./vendor/bin/sail down

## Testing

This project uses Pest.

- Run the full test suite:
- bash
  ./vendor/bin/pest
- Or via Artisan:
- bash
  php artisan test
- With coverage (requires Xdebug/PCOV):
- bash
  ./vendor/bin/pest --coverage

## Code quality and tooling

- Format and lint with Laravel Pint:
- bash
  ./vendor/bin/pint

- Realtime logs with Laravel Pail:
- bash
  php artisan pail

## Optional integrations

- JWT Auth
    - Publish config and generate secret:
    - bash
      php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
      php artisan jwt:secret
    - Add and configure middleware/guards as needed in config/auth.php.

- Stripe
    - Set credentials in .env:
    - env
      STRIPE_KEY=pk_live_or_test_xxx
      STRIPE_SECRET=sk_live_or_test_xxx

- Mail (example SMTP)
    - env
      MAIL_MAILER=smtp
      MAIL_HOST=smtp.example.com
      MAIL_PORT=587
      MAIL_USERNAME=your_smtp_username
      MAIL_PASSWORD=your_smtp_password
      MAIL_ENCRYPTION=tls
      MAIL_FROM_ADDRESS=no-reply@example.com
      MAIL_FROM_NAME="${APP_NAME}"

## Deployment

- Build frontend assets (if applicable):
- bash
  npm ci
  npm run build

- Optimize and cache:
- bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache

- Migrate database:
- bash
  php artisan migrate --force

- Ensure correct permissions for:
    - storage/
    - bootstrap/cache/

## Troubleshooting

- Clear caches:
- bash
  php artisan optimize:clear

- Node/Vite issues:
    - Ensure Node 18+.
    - Remove node_modules and run npm install again if needed.

- Database connection:
    - Verify .env credentials and that MySQL is reachable.
    - Run php artisan migrate to confirm connectivity.

## Contributing

- Create a feature branch from main.
- Follow PSR-12 coding standards.
- Include tests where appropriate.

## License

Specify your project license here (e.g., MIT, Apache-2.0, or proprietary).
