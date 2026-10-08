# Deploying to Lightsail

1. **Create instance**: Lightsail → Linux/Unix → *OS only* → Ubuntu 24.04. Plan: **2 GB RAM minimum** (Tainacan + image processing; 4 GB for large collections). Add a **static IP** and open ports 80/443 in the firewall.
2. **DNS**: point an A record (e.g. `museu.example.com`) to the static IP.
3. **Install Docker**:
   ```bash
   curl -fsSL https://get.docker.com | sh && sudo usermod -aG docker ubuntu
   ```
4. **Deploy**:
   ```bash
   git clone https://github.com/pvalente/museu.git && cd museu
   cp .env.example .env   # DOMAIN=museu.example.com, WP_HOME=https://museu.example.com, strong passwords
   docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
   scripts/setup.sh "Museu" admin you@example.com
   ```
5. **Backups**: enable Lightsail automatic snapshots, and add cron:
   `0 3 * * * cd /home/ubuntu/museu && scripts/backup.sh`
6. **Updates**: `git pull && docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build`; update plugins via wp-admin or `docker compose exec --user www-data wordpress wp plugin update --all`.

Tip: for lots of high-res images, add a Lightsail block-storage disk or offload media to S3.

## No domain yet: temporary URL
`DOMAIN="auto"` in `scripts/lightsail-launch.sh` uses a free `<ip-with-dashes>.sslip.io` name with real HTTPS. Attach the static IP right after creating the instance, then on the server run `sudo /opt/museu/scripts/set-domain.sh` to switch to the final IP. When you get a real domain: `sudo /opt/museu/scripts/set-domain.sh museu.example.com`.
