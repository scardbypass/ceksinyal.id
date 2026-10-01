#!/usr/bin/env bash
set -euo pipefail
APP="${APP_DIR:-$HOME/ceksinyal.id}"
apt-get update
apt-get install -y nginx mariadb-server php8.2-fpm php8.2-cli php8.2-mysql php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip unzip curl git composer certbot python3-certbot-nginx
echo "Clone project ke: $APP"
echo "git clone https://github.com/scardbypass/ceksinyal.id.git $APP"
echo "Nginx root wajib diarahkan ke: $APP/public"
