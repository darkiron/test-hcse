import { apiFetch } from '../api.js';

export async function renderProductsForOffer(offerId) {
  const container = document.getElementById('offers');
  if (!container) return;
  container.innerHTML = `<p>Chargement des produits pour l’offre #${offerId}…</p>`;
  try {
    const res = await apiFetch(`/api/offers/${offerId}/products`);
    if (!res.ok) {
      container.innerHTML = `<p class="has-text-danger">Erreur ${res.status} lors du chargement des produits.</p>`;
      return;
    }
    const body = await res.json();
    container.innerHTML = '';
    if (!Array.isArray(body) || body.length === 0) {
      container.innerHTML = '<p>Aucun produit pour cette offre.</p>';
      return;
    }
    const list = document.createElement('ul');
    body.forEach(p => {
      const li = document.createElement('li');
      li.textContent = `${p.name} [${p.state}]`;
      list.appendChild(li);
    });
    const wrap = document.createElement('article');
    wrap.className = 'message';
    const b = document.createElement('div');
    b.className = 'message-body';
    b.appendChild(list);
    wrap.appendChild(b);
    container.appendChild(wrap);
  } catch (e) {
    container.innerHTML = '<p class="has-text-danger">Erreur lors du chargement des produits.</p>';
  }
}
