# Test technique Senior — HelloCSE (Laravel)

Ce dépôt est une base d’évaluation pour un·e développeur·se senior PHP/Laravel. L’objectif est d’améliorer l’application existante (architecture, qualité, tests, robustesse) sans casser son fonctionnement.

Ce qui est livré dans ce dépot
- Reverse‑proxy Caddy (Nginx supprimé du projet)
- API sécurisée globalement (Sanctum stateful + rate limiting + 401 JSON — aucune redirection web)
- SPA full JavaScript structurée (Atomic Design) avec layout, pages (views) et routeur
- Docker Compose prêt à l’emploi (PHP‑FPM + MySQL + Caddy + Node pour le build front)

---

## Démarrage rapide avec Docker

1) Préparer l’environnement
```bash
cp laravel/.env.example laravel/.env
```
Dans `laravel/.env` (mode Docker), utiliser :
```ini
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

2) Lancer l’infrastructure
```bash
docker compose up -d --build
```
Services exposés :
- web (Caddy) → http://localhost:8080
- app (PHP‑FPM)
- db (MySQL) → port hôte 33060 (interne 3306)
- node (build SPA)

3) Installer et initialiser Laravel
```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

4) Frontend (SPA)
- Le service `node` construit en continu `frontend/dist` (monté en lecture seule côté Caddy).
- Pour forcer un build ponctuel :
```bash
docker compose exec node npm ci
docker compose exec node npm run build
```

Vérifications rapides
- SPA : http://localhost:8080/login (redirige vers /dashboard après login)
- API : http://localhost:8080/api/offers → 401 (anonyme), 200 après authentification

---

## Démarrage local (hors Docker)
Pré‑requis : PHP 8.4+, Composer 2, Node 18+, MySQL/MariaDB (ou SQLite)
```bash
cd laravel
composer install
npm ci

cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

npm run build   # ou npm run dev
php artisan serve
```

---

## Sécurité et flux d’authentification (Sanctum)
- L’API est sécurisée globalement : toutes les routes de lecture/écriture d’offres/produits exigent une session authentifiée (cookies HTTP‑only + CSRF).
- Rate‑limit : 60 requêtes/minute globalement sur `/api/*`. Anti‑bruteforce sur `POST /api/login` : 6/minute.
- Sans cookies valides : l’API renvoie 401 JSON (jamais de redirection web).

Exemple (curl)
```bash
# 1) Obtenir les cookies CSRF
curl -i -c cookies.txt -b cookies.txt http://localhost:8080/sanctum/csrf-cookie

# 2) Extraire le token XSRF
XSRF=$(perl -ne 'print $1 if /XSRF-TOKEN\t([^\n]+)/' cookies.txt | python - <<PY
import urllib.parse,sys;print(urllib.parse.unquote(sys.stdin.read()))
PY
)

# 3) Login (remplacez les identifiants si besoin)
curl -i -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -H "X-XSRF-TOKEN: $XSRF" \
  -c cookies.txt -b cookies.txt \
  --data '{"email":"admin@example.com","password":"secret"}'

# 4) Appels protégés
curl -i -b cookies.txt http://localhost:8080/api/user
curl -i -b cookies.txt http://localhost:8080/api/offers

# 5) Logout
curl -i -X POST -H "X-XSRF-TOKEN: $XSRF" -b cookies.txt http://localhost:8080/api/logout
```

---

## Structure du front (SPA Atomic Design)
Dossier `frontend/` :
- layout/
  - `MainLayout.js` : assemble Navbar + vues
- views/
  - `LoginView.js`, `DashboardView.js`, `OffersView.js`, `ProductsView.js`
- atoms/
  - `Button.js`, `Input.js`
- molecules/
  - `Navbar.js`
- organisms/
  - `LoginCard.js`
- components/
  - `OffersList.js`, `ProductsList.js`
- api/
  - `api.js` : `apiFetch` (credentials + auto‑CSRF), `csrf`, `login`, `me`, `logout`, `listOffers`…
- routeur
  - `router.js` (History API : routes exactes + dynamiques RegExp)
- bootstrap
  - `index.html` minimal, `app.js` (monte le layout), `main.js` (enregistre les routes et les views)

Routes SPA
- `/login` → formulaire (XHR visible) → redirection `/dashboard` après succès
- `/dashboard` → profil (`GET /api/user`)
- `/offers` → listing d’offres (`GET /api/offers` après login)
- `/offers/:id/products` → produits d’une offre

---

## Endpoints API (extrait)
- Auth
  - `GET /sanctum/csrf-cookie` → 204
  - `POST /api/login` (email, password) → 200
  - `POST /api/logout` → 204
  - `GET /api/user` → 200 (auth requis)
- Données (auth requis)
  - `GET /api/offers`
  - `GET /api/offers/{offer}/products`

Sans cookies de session, ces endpoints renvoient 401 JSON.

---

## Tests & Qualité
```bash
docker compose exec app php artisan test
```
Analyse statique (Larastan/PHPStan niveau 8)
```bash
docker compose exec app ./vendor/bin/phpstan analyse --level=8
```

---

## Débogage (Xdebug)
- Xdebug activé côté PHP en dev.
- IDE :
  - Écoute sur 9003
  - Mappings : `/var/www/html` (container) ↔ `./laravel` (host)
- Logs utiles :
```bash
docker compose logs -f web
docker compose logs -f app
```

---

## Organisation du dépôt
```
laravel/         # Application Laravel (code, migrations, tests…)
frontend/        # SPA (Atomic Design)
infra/docker/
  ├─ php/        # Dockerfile PHP-FPM + conf PHP
  └─ caddy/      # Dockerfile + Caddyfile (proxy + SPA + /api)
compose.yaml     # Orchestration Docker
```

Notes
- Nginx a été retiré du repo ; le reverse‑proxy est Caddy. Si vous voyez encore « nginx » dans votre environnement, c’est un reliquat local à nettoyer.
- En production, privilégier des images immuables (composer --no-dev et build front au build d’image plutôt qu’en watch).

Bon dev !
