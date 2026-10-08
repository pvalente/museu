#!/usr/bin/env bash
# Point the site at a hostname and enable HTTPS.
# No argument: use a free temporary name derived from this server's public IP
# (1.2.3.4 -> 1-2-3-4.sslip.io). Re-run after attaching/changing the static IP.
# Usage: scripts/set-domain.sh [museu.example.com]
set -euo pipefail
cd "$(dirname "$0")/.."
source .env
if [ -n "${1:-}" ]; then NEW="$1"
else NEW="$(curl -s http://checkip.amazonaws.com | tr -d '\n' | tr . -).sslip.io"; fi
OLD="${WP_HOME#*://}"
sed -i "s|^DOMAIN=.*|DOMAIN=$NEW|; s|^WP_HOME=.*|WP_HOME=https://$NEW|" .env
COMPOSE="docker compose -f docker-compose.yml -f docker-compose.prod.yml"
$COMPOSE up -d
sleep 5
$COMPOSE exec -T --user www-data wordpress wp search-replace "$OLD" "$NEW" --all-tables --skip-columns=guid || true
$COMPOSE exec -T --user www-data wordpress wp search-replace "http://$NEW" "https://$NEW" --all-tables --skip-columns=guid || true
# Never index a temporary sslip.io name. Real domains keep their current setting; going public is a
# deliberate step: wp option update blog_public 1
case "$NEW" in *sslip.io) $COMPOSE exec -T --user www-data wordpress wp option update blog_public 0 ;; esac
echo "Site: https://$NEW/wp-admin"
