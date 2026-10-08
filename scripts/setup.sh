#!/usr/bin/env bash
# Installs WordPress core config + Tainacan. Run after `docker compose up -d`.
# Usage: scripts/setup.sh "Museu" admin admin@example.com
set -euo pipefail
TITLE="${1:?site title}"; ADMIN="${2:?admin user}"; EMAIL="${3:?admin email}"
source .env
WP="docker compose exec -T --user www-data wordpress wp"

$WP core is-installed 2>/dev/null || {
  PASS=$(openssl rand -base64 18)
  $WP core install --url="$WP_HOME" --title="$TITLE" \
    --admin_user="$ADMIN" --admin_password="$PASS" --admin_email="$EMAIL" --skip-email
  echo "Admin password: $PASS  (save it now)"
}
$WP language core install pt_BR --activate || true
$WP rewrite structure '/%postname%/' --hard
$WP plugin install tainacan --activate
$WP theme install tainacan-interface --activate || true
# Core pt_BR doesn't cover plugins/themes; fetch their translations too.
$WP language plugin install --all pt_BR || true
$WP language theme install --all pt_BR || true
echo "Done. Open $WP_HOME/wp-admin -> Tainacan to create your first collection."
