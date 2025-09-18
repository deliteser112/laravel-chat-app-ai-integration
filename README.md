# Laravel Chat App with AI integration

This repository is Chat App which provides a Docker setup and Makefile to run a Laravel app with Redis and a Pusher‑compatible websockets server (Soketi). It also includes handy `make` commands for building, starting, migrating, seeding, testing, and viewing logs.

---

## What’s inside

- **App (PHP‑FPM 8.1)** — Laravel application in `/var/www/html`
  - PHP extensions: `pdo_pgsql`, `zip`, `pcntl`, `sockets`, and `redis` (PECL)
  - Node.js 20 + npm (for Vite/assets)
  - Composer 2 preinstalled
  - Exposes: `5173` (Vite dev server) and `9000` (php‑fpm, inside the Docker network)
- **Redis** — Cache/queue (port `6379`)
- **Soketi** — Pusher-compatible WebSocket server on `6001` with healthcheck
- **Volumes**
  - Project bind‑mount: `./ → /var/www/html`
  - Anonymous volume for `/var/www/html/node_modules` (keeps host clean)

> Note: The compose file uses `DB_HOST=postgres` and `REDIS_HOST=redis`. Make sure you have a matching `postgres` service if your app uses a database.

---

## Quick start

```bash
# 1) Boot everything
make up

# (or build fresh)
make build

# 2) Initialize the app (composer, key, migrate + seed)
make start

# 3) Tail logs
make logs
```

Once up:

- **Vite (dev)**: http://localhost:5173  
- **Soketi**: ws://localhost:6001 (HTTP healthcheck at `http://localhost:6001`)
- **PHP‑FPM**: listens on `9000` inside the Docker network (use Nginx if you want an HTTP entrypoint)

---

## Makefile commands

```bash
make up                 # docker compose up -d
make down               # docker compose down
make build              # build images then up -d

make start              # build + composer install + key:generate + migrate:fresh --seed
make migrate            # php artisan migrate
make seed               # php artisan db:seed

make test               # php artisan test
make test-coverage      # php artisan test --coverage
make test-coverage-html # vendor/bin/phpunit --coverage-html coverage-report/

make logs               # docker compose logs -f
make sh                 # open a shell in the app container (if defined)
```

> All `composer ...` and `php artisan ...` calls are executed **inside the `app` container** via `docker compose exec app ...`.

---

## Environment variables (excerpt)

- **Laravel / DB / Cache**
  - `DB_HOST=postgres`
  - `REDIS_HOST=redis`
- **Dev File Watching**
  - `CHOKIDAR_USEPOLLING=true`
  - `WATCHPACK_POLLING=true`
- **Soketi (Pusher-compatible)**
  - `SOKETI_DEFAULT_APP_ID=${PUSHER_APP_ID:-app-id}`
  - `SOKETI_DEFAULT_APP_KEY=${PUSHER_APP_KEY:-app-key}`
  - `SOKETI_DEFAULT_APP_SECRET=${PUSHER_APP_SECRET:-app-secret}`
  - `SOKETI_DEFAULT_APP_ENABLE_CLIENT_MESSAGES=true`
  - `SOKETI_DEFAULT_APP_ENABLE_USER_AUTHENTICATION=true`

In your Laravel `.env`, set matching Pusher values (example):

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=app-id
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=soketi
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1
```

> If you’re connecting from the host (e.g., local scripts or another app), use `localhost:6001` instead of `soketi:6001`.

---

## Docker image (app)

The `Dockerfile`:

- Based on `php:8.1-fpm-bookworm`
- Installs system deps: `git`, `curl`, `unzip`, `zip`, `libpq-dev`, `libzip-dev`, `ca-certificates`
- PHP extensions: `pdo_pgsql`, `zip`, `pcntl`, `sockets` + `redis` (PECL)
- Installs Node 20 and Composer 2
- Copies project files; runs a (tolerant) `composer install` during build
- Prepares `/var/www/html/node_modules`
- Creates non‑root user `appuser` (`uid=1000`) and switches to it
- Exposes `9000` and `5173`

---

## Typical workflows

### Install dependencies
```
docker compose exec app composer install
docker compose exec app npm ci
```

### Run Vite dev server
```
docker compose exec app npm run dev
# Open http://localhost:5173
```

### Run migrations/seeds
```
make migrate
make seed
```

### Run tests
```
make test
# or with coverage:
make test-coverage
make test-coverage-html
```

### Open a shell in the app container
```
make sh           # if present
# or
docker compose exec app bash
```

---

## Adding a web server (optional)

This stack exposes PHP‑FPM on port `9000`. To serve HTTP:

- Add an **nginx** service to `docker-compose.yml` that fastcgi‑passes to `app:9000`
- Mount your nginx config
- Publish `80:80` (or `8080:80`) and browse the app via that port

---

## Troubleshooting

- **File change detection**  
  Ensure `CHOKIDAR_USEPOLLING=true` and `WATCHPACK_POLLING=true` (already set). Some tools allow tuning the polling interval.

- **Permissions for `storage` / `bootstrap/cache`**  
  ```
  docker compose exec app php artisan storage:link || true
  docker compose exec app chmod -R ug+rw storage bootstrap/cache
  ```

- **Soketi connection errors**  
  Confirm `.env` Pusher values match compose (host/port: `soketi:6001` in Docker; `localhost:6001` from the host).

- **Composer memory**  
  ```
  docker compose exec -e COMPOSER_MEMORY_LIMIT=-1 app composer install
  ```

---

## Housekeeping

- **Data** — If you add Postgres, persist with a named volume (e.g., `pgdata`).
- **Node modules** — Kept inside the container on an anonymous volume.
- **Logs** — `make logs` tails all service logs.

---

## License

Provided as‑is; adapt to your project’s licensing as needed.
