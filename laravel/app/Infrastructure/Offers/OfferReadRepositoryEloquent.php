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

            /** @var class-string $model */
            $model = '\\App\\Models\\Offer';
            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query = $model::query();

            // Si un scope Published existe, on l’utilise, sinon on tente une
            // convention courante (published / is_published = true)
            if (method_exists(\App\Models\Offer::class, 'scopePublished')) {
                $query = $query->published();
            } else {
                // On ne sait pas le nom exact de la colonne, on essaie "published".
                $query = $query->where('published', true);
            }

            // Charger les produits si la relation existe
            if (method_exists(\App\Models\Offer::class, 'products')) {
                $query = $query->with('products');
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
                        ];
                    }
                }

                $out[] = new OfferDto((int)$offer->id, (string)($offer->title ?? ''), $products);
            }

            return $out;
        } catch (\Throwable) {
            // Squelette: en cas d’erreur (schéma/relations inconnus), renvoyer vide.
            return [];
        }
    }
}
