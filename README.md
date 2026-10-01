<div align="center">

# 📡 CEKSINYAL.ID

### IMEI Services • CEIR Automation • Reseller API • Payment Automation

**Modern IMEI service platform built for multi-level resellers, automated CEIR checks, WhatsApp order routing, deposits, and Open API / Dhru integration.**

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MariaDB](https://img.shields.io/badge/MariaDB-10.5+-003545?logo=mariadb&logoColor=white)](https://mariadb.org/)
[![Node.js](https://img.shields.io/badge/Node.js-18+-339933?logo=nodedotjs&logoColor=white)](https://nodejs.org/)
[![Nginx](https://img.shields.io/badge/Nginx-Recommended-009639?logo=nginx&logoColor=white)](https://nginx.org/)
[![Domain](https://img.shields.io/badge/Production-ceksinyal.id-111827)](https://ceksinyal.id)

</div>

> [!IMPORTANT]
> Project ini memproses saldo dan transaksi. Jangan pernah commit file `.env`, token API, cookie GoBiz, OAuth refresh token, session WhatsApp, password database, atau credential lain ke GitHub.

---

## ✨ Tentang CEKSINYAL.ID

CEKSINYAL.ID adalah panel layanan IMEI dengan tampilan modern startup SaaS. Sistem mendukung produk manual melalui operator WhatsApp maupun produk provider API seperti CeirGo, harga bertingkat untuk reseller, CEIR precheck, refund otomatis, deposit manual/otomatis, ticket support, Open API dan kompatibilitas Dhru.

### Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.2+ |
| Database | MariaDB / MySQL |
| Frontend | Tailwind-style custom UI, Alpine.js, Lucide, SweetAlert2 |
| Web Server | Nginx + PHP-FPM |
| Bot | Node.js 18+ + PM2 |
| Process | PM2 / cron |
| Domain | `ceksinyal.id` |
| API | `api.ceksinyal.id` |

---

## 🚀 Fitur Utama

### User
- Login & register
- Dashboard saldo dan statistik order
- Produk / service IMEI
- Order IMEI dengan validasi **tepat 15 digit**
- Riwayat order dan status
- CEIR checker / CEIR precheck
- Deposit manual
- Deposit otomatis QRIS GoBiz
- Deposit otomatis Bank Jago
- Mutasi saldo
- Ticket bantuan
- API key
- Dokumentasi Open API / Dhru
- Level harga: **User, Seller, Reseller, Grosir, Distributor**

### Admin
- Dashboard admin
- CRUD produk
- Produk ON/OFF satu per satu
- Produk manual atau provider API
- Import service CeirGo
- Group ID WhatsApp berbeda per produk
- CEIR precheck ON/OFF per produk
- Harga per level user
- Edit user, level, status dan saldo
- Order management
- Select one / select all
- Bulk Processing / Done / Failed / Refund
- Riwayat transaksi
- Mutasi / wallet ledger
- Deposit approval manual
- Ticket management
- API logs
- Bot settings
- Payment Gateway settings
- Website settings: nama, logo, branding dan konfigurasi lainnya

---

## 🔄 Cara Kerja Order

```text
User membuat order
        │
        ▼
Validasi IMEI (15 digit)
        │
        ▼
Validasi produk + harga level user
        │
        ▼
CEIR Precheck (jika aktif)
        │
        ├── REGISTERED / ROAMER
        │       └── REJECT → REFUND
        │
        └── UNKNOWN
                │
                ▼
           Debit saldo
                │
        ┌───────┴────────┐
        │                │
   Produk Manual     Provider API
        │                │
 WhatsApp Group       CeirGo
        │                │
 reply D/F        response/webhook
        └───────┬────────┘
                ▼
       SUCCESS / REJECTED
                │
       FAILED → REFUND
```

IMEI hanya diterima jika cocok dengan:

```regex
^\d{15}$
```

Frontend membatasi input dan backend tetap melakukan validasi ulang.

### WhatsApp operator

Setiap produk manual dapat mempunyai `whatsapp_group_id` sendiri.

Contoh pesan:

```text
🔐 NEW IMEI ORDER

Order  : #ORD-2601000123
Service: iPhone Premium
IMEI   : 351234567890123
Status : PROCESSING

Reply:
D / DONE   = Success
F / FAILED = Rejected + Refund
```

Bot membaca quoted message agar reply operator terikat ke order yang benar.

---

## 💰 Wallet, Ledger & Refund

Saldo tidak boleh hanya diubah menggunakan query bebas. Semua perubahan harus menghasilkan ledger.

Contoh:

| Type | Amount | Description |
|---|---:|---|
| DEPOSIT | +100.000 | QRIS |
| ORDER | -25.000 | ORD-001 |
| REFUND | +25.000 | ORD-001 |

Refund dan settlement dibuat **idempotent**. Order/deposit yang sama tidak boleh menambah saldo dua kali walaupun callback atau worker berjalan ulang.

---

## 💳 Deposit & Payment Gateway

Admin dapat mengaktifkan:

- Manual saja
- Otomatis saja
- Manual + otomatis
- GoBiz ON / OFF
- Jago ON / OFF secara independen

### Manual

```text
User request deposit
→ transfer
→ upload bukti
→ admin approve/reject
→ approve = credit wallet
```

### GoBiz QRIS

```text
Create deposit
→ generate dynamic QRIS
→ PENDING
→ user bayar
→ journal reconciliation
→ reference/nominal diverifikasi
→ settlement
→ credit wallet
→ SUCCESS
```

### Jago

```text
Create invoice
→ user transfer
→ Gmail/OAuth transaction source
→ parser transaksi
→ matcher invoice
→ settlement
→ credit wallet
```

> [!CAUTION]
> Jangan aktifkan auto-credit production sebelum format journal GoBiz dan email/notifikasi Jago sudah dites menggunakan transaksi nyata akun sendiri. Jika autentikasi atau parsing gagal, status harus tetap `PENDING`, bukan dipaksa `SUCCESS`.

---

## 🗂️ Struktur Project

```text
ceksinyal.id/
├── app/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── User/
│   │   ├── Admin/
│   │   └── Api/
│   ├── Models/
│   ├── Services/
│   │   └── Provider/
│   ├── Payments/
│   │   └── Gateways/
│   │       ├── Manual/
│   │       ├── GoBiz/
│   │       └── Jago/
│   ├── Middleware/
│   └── Helpers/
├── bot/
├── config/
├── database/
├── public/
├── routes/
├── storage/
├── views/
├── workers/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

# 🛠️ Instalasi VPS

Direkomendasikan Debian 11/12 atau Ubuntu LTS. Folder project tidak wajib `/var/www`; panduan ini memakai `/root/ceksinyal.id` agar sesuai deployment VPS langsung. Jika memakai user non-root, gunakan `/home/<user>/ceksinyal.id` dan arahkan Nginx ke folder `public` di dalamnya.

## 1. Install dependencies

```bash
apt update && apt upgrade -y

apt install -y \
  nginx \
  mariadb-server \
  php8.2-fpm \
  php8.2-cli \
  php8.2-mysql \
  php8.2-curl \
  php8.2-mbstring \
  php8.2-xml \
  php8.2-zip \
  php8.2-gd \
  unzip git curl composer
```

Install Node.js 18+ dan PM2:

```bash
node -v
npm -v

npm install -g pm2
```

## 2. Clone

```bash
cd /root
git clone https://github.com/scardbypass/ceksinyal.id.git
cd ceksinyal.id
```

## 3. PHP dependencies

```bash
composer install --no-dev --optimize-autoloader
```

Jika bot memiliki dependencies:

```bash
npm install --omit=dev
```

## 4. Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE ceksinyal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'ceksinyal'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD_KUAT';

GRANT ALL PRIVILEGES ON ceksinyal.* TO 'ceksinyal'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

Import schema:

```bash
mysql -u ceksinyal -p ceksinyal < database/schema.sql
```

---

# ⚙️ Environment

Copy template:

```bash
cp .env.example .env
nano .env
```

Contoh konfigurasi. **Ganti seluruh placeholder dengan credential milik sendiri.**

```dotenv
APP_NAME="CEKSINYAL"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ceksinyal.id
API_URL=https://api.ceksinyal.id
APP_TIMEZONE=Asia/Jakarta
APP_KEY=CHANGE_TO_LONG_RANDOM_SECRET

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ceksinyal
DB_USERNAME=ceksinyal
DB_PASSWORD=CHANGE_ME

SESSION_SECURE=true
SESSION_SAMESITE=Lax

CEIRGO_ENABLED=true
CEIRGO_BASE_URL=https://ceirgo.id
CEIRGO_API_KEY=CHANGE_ME

MANUAL_DEPOSIT_ENABLED=true
AUTO_DEPOSIT_ENABLED=true

GOBIZ_ENABLED=true
GOBIZ_MERCHANT_ID=CHANGE_ME
GOBIZ_STATIC_QRIS=CHANGE_ME
GOBIZ_ACCESS_TOKEN=CHANGE_ME

JAGO_ENABLED=true
JAGO_GMAIL_CLIENT_ID=CHANGE_ME
JAGO_GMAIL_CLIENT_SECRET=CHANGE_ME
JAGO_GMAIL_REFRESH_TOKEN=CHANGE_ME

WHATSAPP_ENABLED=true
BOT_API_SECRET=CHANGE_TO_RANDOM_INTERNAL_SECRET
BOT_DEFAULT_GROUP_ID=

OPEN_API_ENABLED=true
DHRU_ENABLED=true
```

Generate secret, misalnya:

```bash
openssl rand -hex 32
```

> [!WARNING]
> `.env` harus berada di luar akses publik dan harus ada di `.gitignore`.

---

# 🌐 Nginx

Website:

```nginx
server {
    listen 80;
    server_name ceksinyal.id www.ceksinyal.id;

    root /root/ceksinyal.id/public;
    index index.php;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\. {
        deny all;
    }
}
```

API dapat diarahkan ke public entrypoint yang sama:

```nginx
server {
    listen 80;
    server_name api.ceksinyal.id;

    root /root/ceksinyal.id/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }
}
```

Enable:

```bash
ln -s /etc/nginx/sites-available/ceksinyal.id /etc/nginx/sites-enabled/ceksinyal.id

nginx -t
systemctl reload nginx
```

## SSL

Pastikan DNS `ceksinyal.id` dan `api.ceksinyal.id` sudah menuju VPS.

```bash
apt install -y certbot python3-certbot-nginx

certbot --nginx \
  -d ceksinyal.id \
  -d www.ceksinyal.id \
  -d api.ceksinyal.id
```

---

# 📁 Permission

```bash
chown -R www-data:www-data /root/ceksinyal.id/storage
chown -R www-data:www-data /root/ceksinyal.id/public/uploads

chmod -R 775 /root/ceksinyal.id/storage
chmod -R 775 /root/ceksinyal.id/public/uploads
```

Jangan memberi `777` ke seluruh project.

---

# 🤖 Menjalankan Bot WhatsApp

```bash
cd /root/ceksinyal.id

pm2 start bot/index.js --name ceksinyal-bot
pm2 save
pm2 startup
```

Monitor:

```bash
pm2 status
pm2 logs ceksinyal-bot
```

Bot dan website berkomunikasi melalui internal API menggunakan `BOT_API_SECRET`. Jangan expose secret tersebut di JavaScript frontend.

---

# ⚙️ Workers

Worker payment sebaiknya berjalan terpisah dari bot.

Contoh PM2:

```bash
pm2 start workers/gobiz-worker.js --name ceksinyal-gobiz
pm2 start workers/jago-worker.js --name ceksinyal-jago

pm2 save
```

Jika worker project berupa PHP command, gunakan cron/systemd sesuai command yang tersedia pada source.

Prinsip worker:

1. Ambil deposit `PENDING`.
2. Fetch transaksi provider.
3. Validasi reference, nominal, waktu dan status.
4. Lock invoice.
5. Settlement satu kali.
6. Buat ledger.
7. Commit transaction.
8. Baru tandai `SUCCESS`.

---

# 🏦 Setting Payment Gateway

Masuk:

```text
Admin
→ Settings
→ Payment Gateway
```

Admin dapat mengatur:

```text
Manual Deposit       ON/OFF
Automatic Deposit    ON/OFF

QRIS GoBiz           ON/OFF
Bank Jago            ON/OFF
```

Credential sensitif lebih aman berasal dari `.env`; dashboard admin hanya menyimpan setting operasional/non-secret atau encrypted secret bila implementasi mendukungnya.

---

# 📦 Produk

Produk mendukung:

```text
Source:
- manual
- ceirgo

CEIR Precheck:
- ON
- OFF

Product Status:
- ON
- OFF

WhatsApp Group:
- Group ID per product

Price:
- User
- Seller
- Reseller
- Grosir
- Distributor
```

Produk OFF tidak mengganggu produk lain.

### Import CeirGo

```text
Admin → Products → Import CeirGo
```

Service provider diambil lalu admin memilih service mana yang ingin dijual. Simpan provider/service ID terpisah dari product ID lokal agar provider dapat diganti tanpa merusak order lama.

---

# 👥 User Levels

```text
user
seller
reseller
grosir
distributor
```

Harga menggunakan tabel harga per product/level agar penambahan level baru tidak memerlukan perubahan kolom produk.

---

# 🔌 Open API

Base:

```text
https://api.ceksinyal.id/v1
```

Contoh resource:

```text
GET  /products
GET  /balance
POST /order
GET  /order/{id}
POST /ceir
```

Gunakan API key server-to-server. Terapkan rate limit, log request, scope API dan opsi IP whitelist sebelum membuka akses luas.

---

# 🔗 Dhru

Endpoint kompatibilitas Dhru tersedia melalui konfigurasi API project.

Aktifkan:

```dotenv
DHRU_ENABLED=true
```

Mapping service Dhru harus menggunakan product/service ID lokal, bukan langsung mempercayai ID provider eksternal.

---

# 🎫 Ticket Support

User:

```text
Dashboard → Support → Create Ticket
```

Admin dapat melihat ticket, membalas, mengubah status, dan menutup ticket.

Status yang disarankan:

```text
OPEN
WAITING_USER
WAITING_ADMIN
CLOSED
```

---

# 🎨 Website Settings

Admin → Settings → Website:

- Website name
- Logo
- Favicon
- Description
- Accent/brand configuration
- Registration ON/OFF
- Maintenance mode
- Support contact
- API availability
- Deposit availability
- Bot operational settings

Gunakan `ceksinyal.id` sebagai canonical URL production.

---

# 🛡️ Security Checklist

Sebelum production:

- [ ] `APP_DEBUG=false`
- [ ] Password di-hash menggunakan `password_hash()`
- [ ] CSRF aktif pada form session
- [ ] Admin route memiliki authorization middleware
- [ ] API menggunakan API key/token
- [ ] Internal bot endpoint menggunakan secret berbeda
- [ ] Rate limiting API
- [ ] Prepared statements / PDO
- [ ] Secure + HttpOnly cookies
- [ ] HTTPS wajib
- [ ] `.env` tidak berada di Git
- [ ] Session WhatsApp tidak berada di Git
- [ ] OAuth token tidak berada di Git
- [ ] Backup database terenkripsi
- [ ] Payment callback/worker idempotent
- [ ] Refund idempotent
- [ ] Audit log untuk perubahan saldo
- [ ] Test deposit nominal sama secara bersamaan
- [ ] Test callback duplikat
- [ ] Test bot disconnect
- [ ] Test provider timeout

---

# 🧪 Checklist Setelah Deploy

```text
[ ] Homepage
[ ] Register
[ ] Login
[ ] Logout
[ ] Admin authentication
[ ] Add product
[ ] Edit product
[ ] Product ON/OFF
[ ] Level pricing
[ ] Import CeirGo
[ ] IMEI 14 digit ditolak
[ ] IMEI 15 digit diterima
[ ] IMEI 16 digit ditolak
[ ] CEIR UNKNOWN
[ ] CEIR REGISTERED
[ ] CEIR ROAMER
[ ] Order manual
[ ] Order provider
[ ] WhatsApp routing
[ ] Reply DONE
[ ] Reply FAILED
[ ] Refund
[ ] Double refund protection
[ ] Manual deposit
[ ] GoBiz QRIS
[ ] GoBiz reconciliation
[ ] Jago reconciliation
[ ] Double settlement protection
[ ] Ticket
[ ] API key
[ ] Open API
[ ] Dhru
[ ] Dark/responsive UI
```

---

# 🩺 Troubleshooting

### 502 Bad Gateway

```bash
systemctl status php8.2-fpm
systemctl status nginx
nginx -t
```

Pastikan socket Nginx sesuai PHP-FPM yang terinstall.

### Database connection failed

```bash
mysql -u ceksinyal -p ceksinyal
```

Periksa `DB_HOST`, database, username dan password pada `.env`.

### Bot offline

```bash
pm2 status
pm2 logs ceksinyal-bot --lines 200
```

Jangan menghapus session sebelum memastikan masalah memang berasal dari authentication.

### Deposit tidak masuk

Periksa berurutan:

```text
Invoice PENDING
→ worker hidup?
→ provider auth valid?
→ transaksi terbaca?
→ nominal/reference cocok?
→ already settled?
→ ledger dibuat?
→ wallet berubah?
```

Jangan mengubah deposit menjadi SUCCESS manual di database tanpa membuat ledger yang sesuai.

### Order gagal

Periksa:

```text
storage/logs
CEIR response
provider response
order_logs
bot status
group mapping
wallet ledger
```

---

# 🔄 Update Production

```bash
cd /root/ceksinyal.id

git pull origin main
composer install --no-dev --optimize-autoloader
npm install --omit=dev

pm2 restart ceksinyal-bot
pm2 restart ceksinyal-gobiz
pm2 restart ceksinyal-jago

systemctl reload php8.2-fpm
systemctl reload nginx
```

Selalu backup database sebelum migration.

---

# 🗄️ Backup

Database:

```bash
mkdir -p /root/backups/ceksinyal

mysqldump -u ceksinyal -p ceksinyal \
  > /root/backups/ceksinyal/ceksinyal-$(date +%F-%H%M).sql
```

Backup `.env` secara privat. Jangan upload credential backup ke repository public.

---

<div align="center">

## CEKSINYAL.ID

**IMEI Services & Device Intelligence Platform**

Built for scalable reseller operations, API integrations and automated transaction workflows.

</div>
