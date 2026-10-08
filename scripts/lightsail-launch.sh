#!/usr/bin/env bash
# Paste into Lightsail "Launch script" (Ubuntu 24.04). Edit DOMAIN/EMAIL first.
# DOMAIN: your hostname, or "auto" for a free temporary https://<ip>.sslip.io name.
DOMAIN="auto"
EMAIL="pedro.valente@gmail.com"
set -euxo pipefail
# Give the static IP time to be attached before we read the public IP.
[ "$DOMAIN" = "auto" ] && sleep 120
curl -fsSL https://get.docker.com | sh
git clone https://github.com/pvalente/museu.git /opt/museu
cd /opt/museu
IP=$(curl -s http://checkip.amazonaws.com)
if [ "$DOMAIN" = "auto" ]; then DOMAIN="$(echo $IP | tr . -).sslip.io"; fi
HOME_URL="https://$DOMAIN"
cat > .env <<ENV
DOMAIN=$DOMAIN
WP_DB_NAME=museu
WP_DB_USER=museu
WP_DB_PASSWORD=$(openssl rand -hex 16)
WP_DB_ROOT_PASSWORD=$(openssl rand -hex 16)
WP_HOME=$HOME_URL
ENV
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
until docker compose exec -T wordpress true 2>/dev/null; do sleep 3; done
scripts/setup.sh "Museu" admin "$EMAIL" | tee /root/museu-admin.txt
echo "0 3 * * * cd /opt/museu && scripts/backup.sh" | crontab -
