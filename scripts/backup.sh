#!/usr/bin/env bash
# Dumps DB + uploads, optionally syncs to S3 (set S3_BUCKET). Run from cron on the Lightsail host.
set -euo pipefail
cd "$(dirname "$0")/.."
source .env
TS=$(date +%F-%H%M); mkdir -p backups
docker compose exec -T db sh -c "mariadb-dump -uroot -p\"\$MARIADB_ROOT_PASSWORD\" --single-transaction $WP_DB_NAME" | gzip > "backups/db-$TS.sql.gz"
docker compose run --rm -T -v "$PWD/backups:/backups" wordpress tar czf "/backups/uploads-$TS.tgz" -C /var/www/html/wp-content uploads
find backups -type f -mtime +14 -delete
[ -z "${S3_BUCKET:-}" ] || aws s3 sync backups "s3://$S3_BUCKET/museu/"
