# Notes
1. dockerisation de l’application
2. Séparation app/infra: l’application Laravel est déplacée sous `laravel/` (artisan, app, config, routes, resources, public, tests, composer.json, etc.).
   - Infra Docker conservée dans `infra/docker`.
   - `compose.yaml` reste à la racine et monte le volume `./laravel:/var/www/html`.

3. test usser mdp: password

## Temps de realisation

environ 8h.
