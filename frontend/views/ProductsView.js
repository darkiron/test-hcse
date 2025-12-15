import { showView } from '../view.js';
import { renderProductsForOffer } from '../components/ProductsList.js';

export async function ProductsView(match) {
  showView('offers');
  let offerId = null;
  if (match && match[1]) {
    offerId = parseInt(match[1], 10);
  } else {
    const m = (location.pathname || '').match(/^\/offers\/(\d+)\/products$/);
    if (m) offerId = parseInt(m[1], 10);
  }
  if (offerId) {
    await renderProductsForOffer(offerId);
  }
}
