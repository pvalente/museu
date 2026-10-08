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
# Blocksy + Tainacan's official integration; look tweaks live in wp-content/mu-plugins/museu-blocksy.php.
$WP theme install blocksy --activate
$WP plugin install tainacan-blocksy --activate
# Outgoing email: the image has no MTA and AWS blocks port 25. Configure in Settings -> FluentSMTP
# (Resend: smtp.resend.com:587 TLS, user "resend", password = Resend API key).
$WP plugin install fluent-smtp --activate
# AI metadata suggestions: WordPress AI (API keys go in Settings -> Connectors, never in git) + Tainacan AI.
# Tainacan AI isn't on wordpress.org, so it's pinned to a GitHub release and updated by hand.
$WP plugin install ai --activate
$WP plugin is-installed tainacan-ai ||
  $WP plugin install https://github.com/tainacan/tainacan-ai/releases/download/0.2.0/tainacan-ai.zip
$WP plugin activate tainacan-ai
# Drop bundled extras we don't use; keep the newest default theme as a fallback.
$WP plugin delete akismet hello 2>/dev/null || true
$WP theme delete twentytwentythree twentytwentyfour 2>/dev/null || true
# Core pt_BR doesn't cover plugins/themes; fetch their translations too.
$WP language plugin install --all pt_BR || true
$WP language theme install --all pt_BR || true
# Brazilian time and dates ("8 de outubro de 2026").
$WP option update timezone_string America/Sao_Paulo
$WP option update date_format 'j \d\e F \d\e Y'
$WP option update time_format 'H:i'
# A catalogue, not a blog: no comments or pingbacks.
$WP option update default_comment_status closed
$WP option update default_ping_status closed
# Plugins/themes update themselves; major core versions stay manual (minor/security stay automatic).
$WP plugin auto-updates enable --all --disabled-only || true
$WP theme auto-updates enable --all --disabled-only || true
$WP option update auto_update_core_major disabled
# Keep temporary sslip.io/localhost hostnames out of search engines. Real domains keep their setting;
# going public is a deliberate step: wp option update blog_public 1
case "$WP_HOME" in *sslip.io*|*localhost*) $WP option update blog_public 0 ;; esac
# First run only: static home page (content/home.html), header menu, and remove WP's sample content.
if [ "$($WP option get show_on_front)" != page ]; then
  HOME_ID=$($WP post create - --post_type=page --post_title="Início" --post_name=inicio --post_status=publish --porcelain < content/home.html)
  $WP option update show_on_front page
  $WP option update page_on_front "$HOME_ID"
  $WP post meta update "$HOME_ID" blocksy_post_meta_options '{"has_hero_section":"disabled"}' --format=json
  MENU=$($WP menu create "Principal" --porcelain)
  $WP menu item add-post "$MENU" "$HOME_ID" --title="Início"
  $WP menu item add-custom "$MENU" "Coleções" /colecoes/
  $WP menu item add-custom "$MENU" "Acervo" /itens/
  $WP menu location assign "$MENU" menu_1
  for slug in hello-world sample-page; do
    for id in $($WP post list --post_type=post,page --name="$slug" --field=ID); do $WP post delete "$id" --force; done
  done
fi
echo "Done. Open $WP_HOME/wp-admin -> Tainacan to create your first collection."
