#!/usr/bin/env bash
# Creates the Lightsail instance, static IP and firewall rules with the AWS CLI.
# Requires an authenticated CLI (aws configure / aws sso login).
# Usage: scripts/lightsail-create.sh [region] [bundle]   e.g. sa-east-1 small_3_0
set -euo pipefail
cd "$(dirname "$0")/.."
REGION="${1:-sa-east-1}"; BUNDLE="${2:-small_3_0}"   # small_3_0 = 2 GB RAM
NAME=museu
export AWS_DEFAULT_REGION="$REGION"

aws sts get-caller-identity --query Arn --output text
read -rp "Create '$NAME' ($BUNDLE) in $REGION on this account? [y/N] " ok
[ "$ok" = y ] || exit 1

aws lightsail create-instances --instance-names "$NAME" \
  --availability-zone "${REGION}a" --blueprint-id ubuntu_24_04 --bundle-id "$BUNDLE" \
  --user-data file://scripts/lightsail-launch.sh >/dev/null
echo -n "Waiting for instance"
until [ "$(aws lightsail get-instance-state --instance-name $NAME --query state.name --output text)" = running ]; do echo -n .; sleep 5; done; echo
aws lightsail allocate-static-ip --static-ip-name museu-ip >/dev/null
aws lightsail attach-static-ip --static-ip-name museu-ip --instance-name "$NAME" >/dev/null
aws lightsail put-instance-public-ports --instance-name "$NAME" --port-infos \
  fromPort=22,toPort=22,protocol=tcp fromPort=80,toPort=80,protocol=tcp fromPort=443,toPort=443,protocol=tcp >/dev/null
IP=$(aws lightsail get-static-ip --static-ip-name museu-ip --query staticIp.ipAddress --output text)
echo "IP: $IP"
echo "Site (ready in ~6 min): https://${IP//./-}.sslip.io/wp-admin"
echo "Admin password: aws lightsail ssh isn't in the CLI; use the console's browser SSH -> sudo cat /root/museu-admin.txt"
