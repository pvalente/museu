#!/usr/bin/env bash
# Runs the Tainacan AI eval on the live server: applies prompt/preamble.txt and the collection blueprint
# (field guidance) to the site, then for each case in cases.json uploads its image(s) as temporary media (named
# after the case id), analyzes them against the blueprint's collection, scores Denominação/Classificação against
# the expected values, and deletes the uploads.
# Usage: AWS_PROFILE=museu eval/tainacan-ai/run.sh <label> [model] [effort] [max_tokens]   -> writes results/<date>-<label>.md
#        model: optional Anthropic model id for this run only, e.g. claude-haiku-5-5 (default: the site's; "" to keep it).
#        effort: optional output_config.effort for this run only: low, medium, high, xhigh or max (default: the API's).
#        max_tokens: optional cap for this run only (default: the Tainacan AI setting). Thinking counts toward it.
# Environment (optional):
#        CASES=m1,m2   only these case ids (default: all).
#        MODE=multi    send every view of a case in one request (prototype; Tainacan AI itself sends one image).
#                      Default MODE=single: the first view through the real /tainacan-ai/v1/analyze endpoint.
#        VIEWS=first   with MODE=multi, send only the first view (control for the prototype).
set -euo pipefail
DIR=$(cd "$(dirname "$0")" && pwd)
LABEL=${1:?usage: run.sh <label> [model] [effort] [max_tokens]}
MODEL=${2:-}
EFFORT=${3:-}
MAX_TOKENS=${4:-}
HOST=ubuntu@15.229.74.37
OUT="$DIR/results/$(date +%F)-$LABEL.md"
BLUEPRINT="$DIR/../../scripts/tainacan/inbcm-museologico.json"

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
scp -q "${SSH_OPTS[@]}" "$BLUEPRINT" "$DIR/../../scripts/tainacan/apply-blueprint.php" "$HOST:/tmp/tainacan-ai-eval/php/"

scp -q "${SSH_OPTS[@]}" "$DIR/cases.json" "$HOST:/tmp/tainacan-ai-eval/"

ssh "${SSH_OPTS[@]}" "$HOST" MODEL="$MODEL" EFFORT="$EFFORT" MAX_TOKENS="$MAX_TOKENS" MODE="${MODE:-single}" VIEWS="${VIEWS:-all}" CASES="${CASES:-}" bash -s > "$OUT" <<'REMOTE'
set -e
cd /opt/museu
# docker compose exec reads stdin, which is this script; give it /dev/null so it doesn't eat the rest.
WP() { sudo docker compose exec -T --user www-data wordpress wp "$@" </dev/null; }
X=/tmp/tainacan-ai-eval
sudo docker compose cp $X wordpress:/tmp/ >/dev/null 2>&1
WP eval-file $X/php/apply.php $X/prompt/preamble.txt 2>/dev/null
WP eval-file $X/php/apply-blueprint.php $X/php/inbcm-museologico.json 2>/dev/null | tail -1
# run.php uploads the images it needs and deletes them when it ends.
timeout 3600 sudo docker compose exec -T --user www-data wordpress wp eval-file $X/php/run.php $X/php/inbcm-museologico.json \
  $X/cases.json $X/images "model=$MODEL" "effort=$EFFORT" "max_tokens=$MAX_TOKENS" "mode=$MODE" "views=$VIEWS" "cases=$CASES" </dev/null 2>/dev/null
sudo docker compose exec -T wordpress rm -rf $X </dev/null
rm -rf $X
REMOTE
echo "Wrote $OUT"
