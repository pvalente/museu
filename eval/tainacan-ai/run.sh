#!/usr/bin/env bash
# Runs the Tainacan AI eval on the live server: applies prompt/ to the site, uploads images/ as temporary
# media (titled e1..e8), analyzes each against collection 6's fields, then deletes the uploads.
# Usage: AWS_PROFILE=museu eval/tainacan-ai/run.sh <label>   -> writes results/<date>-<label>.md
set -euo pipefail
DIR=$(cd "$(dirname "$0")" && pwd)
LABEL=${1:?usage: run.sh <label>}
HOST=ubuntu@15.229.74.37
OUT="$DIR/results/$(date +%F)-$LABEL.md"

# Short-lived SSH certificate from Lightsail.
TMP=$(mktemp -d); trap 'rm -rf "$TMP"' EXIT
aws lightsail get-instance-access-details --instance-name museu --protocol ssh --region sa-east-1 --output json > "$TMP/ad.json"
python3 -I -c '
import json, os, sys
d = json.load(open(sys.argv[1]))["accessDetails"]
open(sys.argv[2] + "/key", "w").write(d["privateKey"]); os.chmod(sys.argv[2] + "/key", 0o600)
open(sys.argv[2] + "/key-cert.pub", "w").write(d["certKey"])
' "$TMP/ad.json" "$TMP"
SSH_OPTS=(-i "$TMP/key" -o CertificateFile="$TMP/key-cert.pub" -o UserKnownHostsFile="$TMP/known_hosts" -o StrictHostKeyChecking=accept-new)

ssh "${SSH_OPTS[@]}" "$HOST" 'rm -rf /tmp/tainacan-ai-eval && mkdir /tmp/tainacan-ai-eval'
scp -q "${SSH_OPTS[@]}" -r "$DIR/images" "$DIR/prompt" "$DIR/php" "$HOST:/tmp/tainacan-ai-eval/"

ssh "${SSH_OPTS[@]}" "$HOST" bash -s > "$OUT" <<'REMOTE'
set -e
cd /opt/museu
# docker compose exec reads stdin, which is this script; give it /dev/null so it doesn't eat the rest.
WP() { sudo docker compose exec -T --user www-data wordpress wp "$@" </dev/null; }
X=/tmp/tainacan-ai-eval
sudo docker compose cp $X wordpress:/tmp/ >/dev/null 2>&1
WP eval-file $X/php/apply.php $X/prompt/preamble.txt $X/prompt/fields.json 2>/dev/null
IDS=""
for f in $(ls $X/images/*.jpg | sort); do
  IDS="$IDS $(WP media import "$f" --title="$(basename "$f" .jpg)" --porcelain 2>/dev/null)"
done
WP eval-file $X/php/run.php $IDS 2>/dev/null
WP post delete $IDS --force >/dev/null 2>&1
sudo docker compose exec -T wordpress rm -rf $X </dev/null
rm -rf $X
REMOTE
echo "Wrote $OUT"
