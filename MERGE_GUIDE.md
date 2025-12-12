# Guide de merge — Stratégie simple (develop → main)

## Objectif
Décrire une procédure claire pour merger une branche de travail vers `develop`, puis vers `main` une fois validée.

## Branches
- `main` : branche stable (release).
- `develop` : intégration des features, base des PR courantes.
- `feature/*` ou `chore/*` : branches de travail.

## Procédure standard
1) Ouvrir une PR de votre branche de travail → `develop`.
2) Vérifier et cocher la checklist ci‑dessous.
3) Rebase/squash si nécessaire pour garder un historique propre.
4) Merge vers `develop` (squash & merge recommandé pour les petites PR).
5) Déploiement/vérification en environnement d’intégration (si applicable).
6) Merger `develop` → `main` quand prêt pour release (tag optionnel).

## Checklist avant merge
- [ ] CI verte (tests unitaires/feature).
- [ ] Analyse statique OK (PHPStan, Pint/PHP-CS-Fixer si présent).
- [ ] Migrations testées (up/down) et données de démo cohérentes (seeders).
- [ ] Pas de secrets en clair (env, clés, tokens).
- [ ] Documentation mise à jour (README/NOTES/CHANGELOG le cas échéant).
- [ ] Conflits résolus, rebase recent effectué.

## Commandes utiles (Docker)
```
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan test
docker compose exec app ./vendor/bin/phpstan analyse --level=8
docker compose logs -f app
```

## Conseils commit/PR
- Utiliser Conventional Commits (ex: `feat:`, `fix:`, `chore:`, `docs:`) avec un message court.
- Regrouper les commits verbeux via squash avant merge.
- Lier la PR à l’issue correspondante si applicable.
