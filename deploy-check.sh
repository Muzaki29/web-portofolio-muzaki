#!/bin/bash

# Script untuk cek kesiapan deploy
# Usage: bash deploy-check.sh

echo "🔍 Checking deployment readiness..."
echo ""

# Cek file penting
echo "📁 Checking important files..."
files=(
    "composer.json"
    "railway.json"
    "Procfile"
    "public/cv/muzaki-abdullah-irsyad.pdf"
    "public/images/profile.jpg"
    "routes/web.php"
    "app/Http/Controllers/ContactController.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file MISSING!"
    fi
done

echo ""
echo "🔑 Checking APP_KEY..."
if grep -q "APP_KEY=" .env 2>/dev/null; then
    echo "✅ APP_KEY found in .env"
else
    echo "⚠️  APP_KEY not found. Run: php artisan key:generate"
fi

echo ""
echo "📦 Checking composer dependencies..."
if [ -d "vendor" ]; then
    echo "✅ vendor/ directory exists"
else
    echo "⚠️  vendor/ not found. Run: composer install"
fi

echo ""
echo "✅ Deployment check complete!"
echo ""
echo "📝 Next steps:"
echo "1. Generate APP_KEY: php artisan key:generate --show"
echo "2. Initialize git: git init"
echo "3. Add files: git add ."
echo "4. Commit: git commit -m 'Initial commit'"
echo "5. Push to GitHub: git push origin main"
echo "6. Deploy to Railway/Render (see DEPLOYMENT.md)"

