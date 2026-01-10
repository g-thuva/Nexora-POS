#!/usr/bin/env bash
# Setup script to bootstrap the project on a new machine.
set -euo pipefail

echo "Installing PHP dependencies (composer)..."
composer install --no-interaction

echo "Clearing and optimizing framework caches..."
php artisan view:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan optimize:clear || true

echo "Regenerating optimized autoload..."
composer dump-autoload -o

echo "If you use node assets, run: npm install && npm run build"
echo "Setup complete."
