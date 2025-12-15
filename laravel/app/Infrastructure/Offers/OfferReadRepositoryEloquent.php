<?php
declare(strict_types=1);

namespace App\Infrastructure\Offers;

use App\Application\Offers\Dto\OfferDto;
use App\Domain\Offers\Repositories\OfferReadRepository;

/**
 * Implémentation de lecture basée sur Eloquent.
 * Note: non câblée pour l’instant (aucun binding dans le conteneur),
 * afin de ne pas impacter l’existant. Sert d’exemple d’adaptation Infra.
 */
final class OfferReadRepositoryEloquent implements OfferReadRepository
{
    /**
     * @return iterable<OfferDto>
     */
    public function getPublished(): iterable
    {
        // Par prudence (squelette), on encapsule dans un try/catch afin de
        // ne pas casser l’exécution si les modèles/champs diffèrent.
        try {
            // On référence les modèles de manière paresseuse pour éviter
            // une dépendance forte si les classes n'existent pas encore.
            if (!class_exists('App\\Models\\Offer')) {
                return [];
            }

            $query = \App\Models\Offer::query();

            // Notre schéma a une colonne d'état texte; on retient uniquement 'published'
            $query->where('state', 'published');

            // Charger les produits publiés uniquement si la relation existe
            if (method_exists(\App\Models\Offer::class, 'products')) {
                $query = $query->with(['products' => function ($q) {
                    /** @var \Illuminate\Database\Eloquent\Builder $q */
                    $q->where('state', 'published');
                }]);
            }

            $offers = $query->get();

            $out = [];
            foreach ($offers as $offer) {
                $products = [];
                if (isset($offer->products)) {
                    foreach ($offer->products as $p) {
                        $products[] = [
                            'id' => (int)($p->id ?? 0),
                            'name' => (string)($p->name ?? ''),
                            'state' => (string)($p->state ?? ''),
                        ];
                    }
                }

                $out[] = new OfferDto(
                    (int)$offer->id,
                    (string)($offer->name ?? ''),
                    (string)($offer->state ?? ''),
                    $products
                );
            }

            return $out;
        } catch (\Throwable) {
            // Squelette: en cas d’erreur (schéma/relations inconnus), renvoyer vide.
            return [];
        }
    }
}
