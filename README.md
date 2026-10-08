# Museu

WordPress + [Tainacan](https://tainacan.org) for cataloguing museum pieces, hosted on AWS Lightsail.

Stack: WordPress (PHP 8.3/Apache) · MariaDB · Caddy (HTTPS) · Docker Compose.

## Local development
```bash
cp .env.example .env        # edit passwords
docker compose up -d --build
scripts/setup.sh "Museu" admin you@example.com   # installs WP + Tainacan
```
Open http://localhost:8080/wp-admin → **Tainacan** → create a collection (e.g. "Acervo") and metadata.

## Deploy to Lightsail
See [docs/lightsail.md](docs/lightsail.md).

## Layout
- `wp-content/themes`, `wp-content/mu-plugins` – custom code tracked in git
- Plugins (Tainacan) and uploads live in Docker volumes, not git
- `scripts/backup.sh` – DB + uploads backup (optional S3 sync)
