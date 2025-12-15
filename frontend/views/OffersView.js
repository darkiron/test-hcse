import { showView } from '../view.js';
import { renderOffers } from '../components/OffersList.js';

export async function OffersView() {
  showView('offers');
  await renderOffers();
}
