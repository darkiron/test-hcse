# Architecture DDD (light)

Objectif: introduire une structure inspirée DDD sans casser l’existant.

## Couches et dépendances

- Domain (pure PHP, sans framework)
  - Entités, Value Objects, Interfaces de repository, Domain Services, Exceptions
  - Pas de dépendance à Laravel/Eloquent

- Application (orchestration de cas d’usage)
  - Use Cases (Commands/Queries + Handlers), DTOs
  - Dépend du Domain (interfaces), pas d’infra

- Infrastructure (implémentations techniques)
  - Repositories Eloquent, Mappers, Adapters I/O
  - Dépend de Laravel/Eloquent, implémente les interfaces du Domain

## Règles

- Domain ne connaît pas Laravel.
- Application ne connaît pas Eloquent; parle aux interfaces du Domain.
- Infrastructure ne remonte pas dans Domain.

## Plan d’adoption progressive

1) Introduire les interfaces et VO Domain pour une feature (Offers)
2) Implémenter un use case de lecture simple (GetPublishedOffers)
3) Fournir une implémentation Eloquent (Infrastructure)
4) Binder via un Service Provider (plus tard) et migrer les contrôleurs progressivement

Ce dépôt inclut un squelette initial non branché à l’exécution pour éviter toute régression.
