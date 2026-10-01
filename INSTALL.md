# CEKSINYAL.ID Install

Folder project tidak wajib `/var/www`. Gunakan `~/ceksinyal.id` atau path lain dan arahkan Nginx ke `<path>/public`. Lanjutkan dengan `cp .env.example .env`, `composer install --no-dev --optimize-autoloader`, import `database/schema.sql`, lalu install dependency bot dari folder `bot`.
