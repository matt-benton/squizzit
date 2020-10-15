# Squizzit

> A Vue and Laravel SPA for making, taking, and sharing quizzes.

## Installation

```bash
# Install PHP dependencies
composer install
```

```bash
# Install JavaScript and CSS dependencies
npm install
```

```bash
# Migrate the database
php artisan migrate
```

```bash
# Generate app key
php artisan key:generate
```

```bash
# Run server
php artisan serve
```

```bash
# Compile frontend assets
npm run dev
```

## Running Tests

### Browser Tests

```bash
# Install the latest version of ChromeDriver
php artisan dusk:chrome-driver
```

```bash
# Run tests
php artisan dusk

```

### Unit Tests

```bash
vendor/bin/phpunit
```
