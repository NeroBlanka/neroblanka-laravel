#!/bin/sh
# Boot script Neroblanka — durci pour éviter les healthcheck timeout silencieux.
# Tout fail rend une cause LISIBLE dans les logs Railway, au lieu d'un
# "Stopping Container" muet après 5min de retry.
set -e

echo "🟢 Boot Neroblanka — checking env vars…"

# ── FATAL si manquant : l'app ne peut pas démarrer ──────────────────────────
for v in APP_KEY DB_HOST DB_PASSWORD DB_DATABASE DB_USERNAME; do
  eval "val=\${$v}"
  if [ -z "$val" ]; then
    echo "❌ FATAL: $v est vide. Boot aborté — set la variable dans Railway."
    exit 1
  fi
done

# ── WARN si manquant : features dégradées mais boot OK ──────────────────────
WARN_COUNT=0
for v in AWS_ACCESS_KEY_ID AWS_SECRET_ACCESS_KEY AWS_ENDPOINT AWS_BUCKET RESEND_KEY; do
  eval "val=\${$v}"
  if [ -z "$val" ]; then
    echo "⚠️  WARN: $v est vide — la feature qui en dépend va planter à l'usage."
    WARN_COUNT=$((WARN_COUNT + 1))
  fi
done
if [ "$WARN_COUNT" -gt 0 ]; then
  echo "⚠️  $WARN_COUNT variable(s) optionnelle(s) manquante(s). Boot continue."
fi

# ── Build packages.php SANS booter les routes ──────────────────────────────
# composer install est lancé avec --no-scripts dans le Dockerfile pour éviter
# le catch-22 package:discover ↔ routes Livewire. On construit ici le manifest
# manuellement via PackageManifest::build() qui scanne vendor/ sans charger
# routes/web.php (qui réfère BriefWizard::class, binding registré par Livewire).
echo "🟢 Building package manifest (sans charger les routes)…"
if ! php -r 'require "vendor/autoload.php"; (new Illuminate\Foundation\PackageManifest(new Illuminate\Filesystem\Filesystem(), getcwd(), getcwd() . "/bootstrap/cache/packages.php"))->build();'; then
  echo "❌ FATAL: package manifest build a échoué."
  exit 1
fi

# ── Validation Laravel : si la config charge pas, on le voit AVANT le serveur ─
echo "🟢 Caching config + routes (canary du boot Laravel)…"
if ! php artisan config:cache 2>&1; then
  echo "❌ FATAL: config:cache a échoué — un Service Provider throw au boot."
  exit 1
fi
if ! php artisan route:cache 2>&1; then
  echo "❌ FATAL: route:cache a échoué — une closure dans routes/web.php est invalide."
  exit 1
fi
echo "🟢 Laravel boot OK"

# ── Migrations avec retry (DB Railway parfois lente à devenir reachable) ────
echo "🟢 Running migrations (jusqu'à 5 tentatives)…"
for i in 1 2 3 4 5; do
  if php artisan migrate --force; then
    echo "🟢 Migrations OK"
    break
  fi
  if [ "$i" = 5 ]; then
    echo "❌ FATAL: migrate fail x5 — DB unreachable ou migration cassée."
    exit 1
  fi
  echo "⚠️  migrate tentative $i échouée, retry dans 3s…"
  sleep 3
done

# ── Bootstrap admin password (idempotent) ───────────────────────────────────
# Remplace 'changeme_before_deploy' par la valeur courante d'ADMIN_PASSWORD
# si l'admin l'a encore. No-op sinon — un mdp légitime n'est jamais écrasé.
echo "🟢 Ensuring admin password is not the default…"
php artisan admin:ensure-password || echo "⚠️  admin:ensure-password a échoué (non-bloquant)"

# ── Queue worker détaché et auto-redémarrant ────────────────────────────────
# Un crash du worker ne doit JAMAIS tuer le container (sinon healthcheck flap).
echo "🟢 Starting queue worker (detached, auto-restart on crash)…"
(while :; do
  php artisan queue:work --queue=emails,ai,default --sleep=3 --tries=3 --max-time=3600 || true
  echo "⚠️  queue:work exited, restart dans 5s…"
  sleep 5
done) &

# ── HTTP serveur ────────────────────────────────────────────────────────────
echo "🟢 HTTP server starting on 0.0.0.0:${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
