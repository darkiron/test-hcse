import { listOffers } from '../api.js';

export async function renderOffers() {
  const container = document.getElementById('offers');
  if (!container) return;
  container.innerHTML = '';
  const header = document.createElement('div');
  header.className = 'mb-2';
  header.textContent = 'Chargement des offres…';
  container.appendChild(header);
  try {
    const offers = await listOffers();
    container.innerHTML = '';
    if (!Array.isArray(offers) || offers.length === 0) {
      container.innerHTML = '<p>Aucune offre publiée.</p>';
      return;
    }
    offers.forEach(o => {
      const box = document.createElement('article');
      box.className = 'message';
      const h = document.createElement('div');
      h.className = 'message-header';
      const link = document.createElement('a');
      link.setAttribute('data-link', '');
      link.href = `/offers/${o.id}/products`;
      link.textContent = o.name;
      const p = document.createElement('p');
      const small = document.createElement('small');
      small.style.marginLeft = '8px';
      small.style.opacity = '.7';
      small.textContent = `#${o.id} • ${o.state}`;
      p.appendChild(link);
      p.appendChild(small);
      h.appendChild(p);
      const b = document.createElement('div');
      b.className = 'message-body';
      const ul = document.createElement('ul');
      (o.products || []).forEach(p => {
        const li = document.createElement('li');
        li.textContent = `${p.name} [${p.state}]`;
        ul.appendChild(li);
      });
      b.appendChild(ul);
      box.appendChild(h);
      box.appendChild(b);
      container.appendChild(box);
    });
  } catch (e) {
    container.innerHTML = '<p class="has-text-danger">Erreur lors du chargement des offres.</p>';
  }
}
