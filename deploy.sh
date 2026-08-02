#!/usr/bin/env bash
#
# Script de déploiement, à exécuter EN SSH depuis le dossier "private/"
# (la racine Laravel sur le serveur), après avoir uploadé les fichiers.
#
#   Hébergement mutualisé type cPanel/DirectAdmin :
#     web/     = racine publique (contenu du dossier public/ de Laravel)
#     private/ = le reste du projet Laravel (app, config, vendor, .env, ...)
#
# Usage :  cd ~/private && bash deploy.sh
#
set -e

echo "==> 1. Dépendances PHP (prod)"
if command -v composer >/dev/null 2>&1; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "   composer introuvable, assure-toi d'avoir uploadé le dossier vendor/."
fi

echo "==> 2. Assets front (Vite)"
if command -v npm >/dev/null 2>&1; then
    npm ci --omit=dev || npm install
    npm run build
    echo "   -> copie public/build vers la racine web/"
    mkdir -p ../web/build && cp -r public/build/* ../web/build/
else
    echo "   npm introuvable, build en local puis uploade public/build/ dans web/build/."
fi

echo "==> 3. Base de données (migrations)"
php artisan migrate --force

echo "==> 4. Lien symbolique du stockage (logos, couvertures)"
# public/storage -> storage/app/public, MAIS ici la racine publique est ../web
if [ ! -e ../web/storage ]; then
    ln -s "$(pwd)/storage/app/public" ../web/storage \
        && echo "   -> lien créé : web/storage" \
        || echo "   !! Impossible de créer le lien, crée-le à la main via le gestionnaire de fichiers."
else
    echo "   -> web/storage existe déjà."
fi

echo "==> 5. Optimisation des caches"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> 6. (Optionnel) Super-Admin plateforme"
echo "   Crée un compte plateforme si besoin :"
echo "   php artisan tinker --execute=\"App\\Models\\User::create(['name'=>'Admin','email'=>'admin@tondomaine.com','role'=>'Super','hotel_id'=>null,'password'=>bcrypt('CHANGE_MOI'),'random_key'=>Illuminate\\Support\\Str::random(60)]);\""

echo ""
echo "✅ Déploiement terminé."
echo "   Vérifie : APP_ENV=production, APP_DEBUG=false, DEBUGBAR_ENABLED=false,"
echo "   MAIL_MAILER=smtp (+ identifiants) et APP_URL=https://tondomaine dans .env"
